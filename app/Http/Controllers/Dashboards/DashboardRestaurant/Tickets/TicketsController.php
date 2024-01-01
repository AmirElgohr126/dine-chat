<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Tickets;

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
            'image' => 'nullable|image|max:2048',
        ]);

        // Create the ticket
        $ticket = new Ticket($validatedData);
        $ticket->restaurant_user_id = $request->user('restaurant');
        $ticket->save();
        return finalResponse('success',200,$ticket);
    }


    public function listTiketsForUser(Request $request)
    {
        $userId = $request->user('restaurant')->id;
        $tickets = Ticket::where('restaurant_user_id', $userId)->get();
        return finalResponse('success', 200, $tickets);
    }



    public function storeMessage(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'message' => 'required|string',
        ]);

        $message = new MessagesRestaurantsSupport([
            'ticket_id' => $validatedData['ticket_id'],
            'message' => $validatedData['message'],
        ]);
        // Return a response, e.g., redirect or JSON response
        return finalResponse('success', 200, $message);
    }



    public function getMessagesForTicket(Request $request, $ticketId)
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
?>
