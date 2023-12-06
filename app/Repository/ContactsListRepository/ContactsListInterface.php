<?php
namespace App\Repository\ContactsListRepository;

use Illuminate\Http\Request;
use App\Http\Requests\Contacts\ContactsRequest;
use App\Http\Requests\Contacts\FollowContactRequest;
use App\Http\Requests\Contacts\UnfollowContactRequest;

interface ContactsListInterface
{
        public function getContactList (Request $request);
        public function postContactList(ContactsRequest $request);

        public function followContact (FollowContactRequest $request);

        public function unfollowContact (UnfollowContactRequest $request);
        public function inviteContact(UnfollowContactRequest $request);
}

?>
