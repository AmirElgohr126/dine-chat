<?php
namespace App\Repository\V2\ContactsListRepository;

use Illuminate\Http\Request;
use App\Http\Requests\V2\App\Contacts\ContactsRequest;
use App\Http\Requests\V2\App\Contacts\FollowContactRequest;
use App\Http\Requests\V2\App\Contacts\UnfollowContactRequest;



interface ContactsListInterface
{
        public function getContactList (Request $request);
        public function postContactList(ContactsRequest $request);

        public function followContact (FollowContactRequest $request);

        public function unfollowContact (UnfollowContactRequest $request);

        public function inviteContact(UnfollowContactRequest $request);
}

