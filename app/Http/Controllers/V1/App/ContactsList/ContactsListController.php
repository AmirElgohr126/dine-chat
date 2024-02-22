<?php

namespace App\Http\Controllers\V1\App\ContactsList;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\V1\App\Contacts\ContactsRequest;
use App\Http\Requests\V1\App\Contacts\FollowContactRequest;
use App\Http\Requests\V1\App\Contacts\UnfollowContactRequest;
use App\Repository\V1\ContactsListRepository\ContactsListInterface;

class ContactsListController extends Controller
{
    public $repo ;

    public function __construct(ContactsListInterface $contactsListInterface)
    {
        $this->repo = $contactsListInterface;
    }



    public function getContactList (Request $request)
    {
        // get all contacts list by user_id
        try{
            $result = $this->repo->getContactList($request);
            return finalResponse1('success',200,$result);
        }catch(Exception $th){
            return finalResponse('failed',$th->getCode(),null,null,$th->getMessage());
        }
    }




    public function postContactList(ContactsRequest $request)
    {
        // post all contacts and check if duplcate by phone and make subscriped followed by user
        try{
            $savedContacts = $this->repo->postContactList($request);
            return finalResponse('success',200,$savedContacts);
        } catch (Exception $th) {
            return finalResponse('failed',400,null,null, $th->getMessage());
        }
    }





    public function followContact (FollowContactRequest $request)
    {
        try {
            $this->repo->followContact($request);
            return finalResponse('success',200,__('errors.you_follow_this_contact'));
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }



    public function unfollowContact (UnfollowContactRequest $request)
    {
        try {
            $this->repo->unfollowContact($request);
            return finalResponse('success',200);
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }




    public function inviteContact(UnfollowContactRequest $request)
    {
        try {
            $this->repo->inviteContact($request);
            return finalResponse('success',200);
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }


}
