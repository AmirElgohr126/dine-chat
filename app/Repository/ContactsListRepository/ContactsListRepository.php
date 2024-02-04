<?php
namespace App\Repository\ContactsListRepository;
use Exception;
use App\Models\User;
use App\Models\Contact;
use App\Models\UserFollower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Contacts\ContactsRequest;
use App\Http\Requests\Contacts\FollowContactRequest;
use App\Http\Requests\Contacts\UnfollowContactRequest;
use App\Repository\ContactsListRepository\ContactsListInterface;

Class ContactsListRepository implements ContactsListInterface{



    /**
     * Get paginated contact list.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getContactList(Request $request)
    {
        $contacts = Contact::where('user_id',$request->user()->id)->select(["id","name","photo","phone","status_on_app"])->get();
        return $contacts;
    }
    /**
     * Save new contacts to the database.
     *
     * @param \App\Http\Requests\Contacts\ContactsRequest $request
     * @return mixed
     * @throws \Exception
     */
    // In your repository or service
    public function postContactList(ContactsRequest $request)
    {
        $contacts = $request->validated();
        // Retrieve all phones at once
        $phones = Contact::pluck('phone')->all();
        $phonesUser = User::pluck('phone')->all();
        $savedContacts = []; // Initialize an array to store saved contacts
        foreach ($contacts['contact'] as $contact) // Loop through each contact from the request
        {
            if (in_array($contact['phone'], $phones)) {
                continue; // Skip if the phone is already in the contacts
            }

            if (in_array($contact['phone'], $phonesUser)) {
                $contact['status_on_app'] = 'subscrib';  // Set status to 'subscrib' if the phone is in users
            } else {
                $contact['status_on_app'] = 'not_subscrib'; // Set status to 'not_subscribed' if the phone is not in users
            }
            $userId = $request->user()->id;
            // Handle photo if set
            if (isset($contact['photo'])) {
                $contact['photo'] = storeFile($contact['photo'], "users/user$userId/contacts", 'public');
            } else {

                $contact['photo'] = 'Dafaults\Contacts\avatar.png';
            }
            $contact['user_id'] = $userId;
            Contact::create($contact);
            $savedContacts[] = $contact;
        }
        return $savedContacts;
    }

    /**
     * Follow a contact.
     *
     * @param \App\Http\Requests\Contacts\FollowContactRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function followContact(FollowContactRequest $request)
    {
            $id = $request->validated('contact_id');
            $user = $request->user();
            $contact = Contact::findOrfail($id);
            if($contact->status_on_app == 'not_subscrib')
            {
                throw new Exception(__('errors.can_not_follow'),400);
            }

            $isFollowing = UserFollower::where('user_id', $user->id)->where('contact_id', $contact->id)->exists();
            if ($isFollowing) {
                throw new Exception(__("errors.already_following"),400);
            }
            $followedUser = User::select(['id','phone'])->where('phone',$contact->phone)->first();

            if($followedUser->phone != $contact->phone)
            {
                throw new Exception(__('errors.error_in_contact'),500);
            }

            $follow = UserFollower::create(['user_id'=>$user->id,'followed_user' => $followedUser->id,'contact_id'=>$id,'follow_status'=>'follow']);
            if ($follow) {
                return $follow;
            } else {
                throw new Exception(__('errors.Error_Processing_Request'),500);
            }
    }
    /**
     * Unfollow a contact.
     *
     * @param \App\Http\Requests\Contacts\UnfollowContactRequest $request
     */
    public function unfollowContact(UnfollowContactRequest $request)
    {
        $isFollowing = UserFollower::where('user_id', $request->user()->id)->where('contact_id', $request->contact_id)->first();
        if($isFollowing)
        {
            $isFollowing->delete();
        }
    }

    /**
     * Invite a contact to the app.
     *
     * @param \App\Http\Requests\Contacts\UnfollowContactRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function inviteContact(UnfollowContactRequest $request)
    {
        $id = $request->validated('contact_id');
        $user = $request->user();
        $contact = Contact::find($id);
        if(!$contact->status_on_app == 'not_subscrib')
        {
            throw new Exception(__('errors.can_not_invite'),400);
        }
        $follow = UserFollower::create(['user_id'=>$user->id,'contact_id'=>$id,'follow_status'=>'invited']);
        if ($follow) {
            // Mail::to($contact->email)->send(new inviteContact());
            return $follow;
        } else {
            throw new Exception(__('errors.Error_Processing_Request'),500);
        }
    }
}

?>
