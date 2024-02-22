<?php
namespace App\Http\Controllers\V2\Dashboards\DashboardRestaurant\Tickets;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\MessagesRestaurantsSupport;

class TicketsController extends Controller
{

/**
     * Create a new ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
        ]);
        $validatedData['image'] = 'Dafaults/Tikets/tekit.webp';
        // Create the ticket
        $ticket = Ticket::create([
                'title' => $validatedData['title'],
                'image' => 'Dafaults/Tikets/tekit.webp',
                'restaurant_user_id' => $request->user('restaurant')->id,
            ]);
        return finalResponse('success',200,$ticket);
    }


    public function listTiketsForUser(Request $request)
    {
        $userId = $request->user('restaurant')->id;
        $tickets = Ticket::where('restaurant_user_id', $userId)->latest()->get();
        return finalResponse('success', 200, $tickets);
    }



    public function storeMessage(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string',
        ]);

        $message = MessagesRestaurantsSupport::create([
            'ticket_id' => $validatedData['ticket_id'],
            'message' => $validatedData['message'],
        ]);
        return finalResponse('success', 200, $message);
    }



    public function getMessagesForTicket(Request $request)
    {
        // Validate the ticket ID
        $validatedData = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $userId = $request->user('restaurant')->id;

        $ticket = Ticket::where('id', $validatedData['ticket_id'])
            ->where('restaurant_user_id', $userId)
            ->first();

        if (!$ticket) {
            return finalResponse('error', 404, 'Ticket not found or access denied.');
        }

        $messages = MessagesRestaurantsSupport::where('ticket_id', $ticket->id)->get();

        return finalResponse('success', 200, $messages);
    }
}
