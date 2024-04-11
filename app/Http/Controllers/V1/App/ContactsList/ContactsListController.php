<?php

namespace App\Http\Controllers\V1\App\ContactsList;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


use App\Http\Requests\V1\App\Contacts\ContactsRequest;
use App\Http\Requests\V1\App\Contacts\FollowContactRequest;
use App\Http\Requests\V1\App\Contacts\UnfollowContactRequest;
use App\Repository\V1\ContactsListRepository\ContactsListInterface;

class ContactsListController extends Controller
{
    /**
     * @var ContactsListInterface
     */
    public ContactsListInterface $repo ;

    public function __construct(ContactsListInterface $contactsListInterface)
    {
        $this->repo = $contactsListInterface;
    }



    /**
     * get all contacts list by user_id
     * @param Request $request
     * @return JsonResponse
     */
    public function getContactList (Request $request): JsonResponse
    {
        // get all contacts list by user_id
        try{
            $result = $this->repo->getContactList($request);
            return finalResponse1('success',200,$result);
        }catch(Exception $th){
            return finalResponse('failed',$th->getCode(),null,null,$th->getMessage());
        }
    }




    /**
     * post all contacts and check if duplcate by phone and make subscriped followed by user
     * @param ContactsRequest $request
     * @return JsonResponse
     */
    public function postContactList(ContactsRequest $request): JsonResponse
    {
        // post all contacts and check if duplicate by phone and make subscribed followed by user
        try{

            $savedContacts = $this->repo->postContactList($request);
            return finalResponse('success',200,$savedContacts);
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }





    /**
     * follow contact by user_id
     * @param FollowContactRequest $request
     * @return JsonResponse
     */
    public function followContact (FollowContactRequest $request): JsonResponse
    {
        try {
            $this->repo->followContact($request);
            return finalResponse('success',200,__('errors.you_follow_this_contact'));
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }



    /**
     * unfollow contact by user_id
     * @param UnfollowContactRequest $request
     * @return JsonResponse
     */
    public function unfollowContact (UnfollowContactRequest $request): JsonResponse
    {
        try {
            $this->repo->unfollowContact($request);
            return finalResponse('success',200);
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }




    /**
     * invite contact by user_id
     * @param UnfollowContactRequest $request
     * @return JsonResponse
     */
    public function inviteContact(UnfollowContactRequest $request): JsonResponse
    {
        try {
            $this->repo->inviteContact($request);
            return finalResponse('success',200);
        } catch (Exception $th) {
            return finalResponse('failed',$th->getCode(),null,null, $th->getMessage());
        }
    }


}
