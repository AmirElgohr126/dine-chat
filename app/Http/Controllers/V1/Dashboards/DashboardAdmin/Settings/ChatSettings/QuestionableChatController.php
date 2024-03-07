<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Settings\ChatSettings;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\App\Chats\ChatHasProfanity;

class QuestionableChatController extends Controller
{

    /**
     * get Questionable Chat (users and his chats)
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function getQuestionableChat(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $chats = Conversation::where('has_profanity', true)->paginate($per_page);
        $pagnateConversation = pagnationResponse($chats);
        $conversations = ChatHasProfanity::collection($chats);
        return finalResponse('success',200,$conversations,$pagnateConversation);
    }


    /**
     * accept Questionable Chat (users and his chats)
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function acceptQuestionableChat(Request $request)
    {
        $chats = Conversation::findOrFail($request->chat_id);
        $chats->has_profanity = false;
        $chats->save();
        return finalResponse('success', 200,'Chat has been accepted successfully');
    }




    /**
     * reject Questionable Chat (users and his chats)
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function rejectQuestionableChat(Request $request)
    {
        $chats = Conversation::findOrFail($request->chat_id);
        $chats->has_profanity = true;
        $chats->delete();
        return finalResponse('success', 200,'Chat has been rejected successfully');
    }

}

