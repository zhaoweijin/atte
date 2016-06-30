<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use Validator;
use Auth;
/*
  Attendize.com   - Event Management & Ticketing
 */

class EventTicketsController extends MyBaseController
{
    /**
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function showTickets(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at' => 'Creation date',
//            'title' => 'Ticket title',
//            'quantity_sold' => 'Quantity sold',
//            'sales_volume' => 'Sales volume',
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'id';
        }

        // Find event or return 404 error.
        $event = Event::scope()->find($event_id);
        if ($event === null) {
            abort(404);
        }


        $cost = $event->tickets()->where('state', '=', 0)->count();



        // Get tickets for event.
        $tickets = empty($q) === false
            ? $event->tickets()->where('title', 'like', '%'.$q.'%')->orderBy($sort_by, 'desc')->get()
            : $event->tickets()->orderBy($sort_by, 'desc')->get();

        // Return view.
        return view('ManageEvent.Tickets', compact('cost','event', 'tickets', 'sort_by', 'q', 'allowed_sorts'));
    }

    /**
     * Show the info ticket modal
     *
     * @param $event_id
     * @return mixed
     */
    public function showInfoTicket($event_id)
    {
        $data = [
//            'ticket' => Ticket::scope()->find($event_id),
            'ticket' => Ticket::scope()->where('event_id', '=', $event_id)->get(),
        ];

        return view('ManageEvent.Modals.InfoTicket', $data);
    }

    /**
     * Show the edit ticket modal
     *
     * @param $event_id
     * @param $ticket_id
     * @return mixed
     */
    public function showEditTicket($event_id, $ticket_id)
    {
        $data = [
            'event'  => Event::scope()->find($event_id),
            'ticket' => Ticket::scope()->find($ticket_id),
        ];

        return view('ManageEvent.Modals.EditTicket', $data);
    }

    /**
     * Show the create ticket modal
     *
     * @param $event_id
     * @return \Illuminate\Contracts\View\View
     */
    public function showCreateTicket($event_id)
    {
        return view('ManageEvent.Modals.CreateTicket', [
            'event' => Event::scope()->find($event_id),
        ]);
    }

    /**
     * Creates a ticket
     *
     * @param $event_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateTicket(Request $request, $event_id)
    {
        $ticket = Ticket::createNew();

        if (!$ticket->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $ticket->errors(),
            ]);
        }

        $ticket->event_id           = $event_id;
        $ticket->title              = $request->get('title');
        $ticket->quantity_available = !$request->get('quantity_available') ? null : $request->get('quantity_available');
        $ticket->start_sale_date    = $request->get('start_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('start_sale_date')) : null;
        $ticket->end_sale_date      = $request->get('end_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('end_sale_date')) : null;
        $ticket->price              = $request->get('price');
        $ticket->min_per_person     = $request->get('min_per_person');
        $ticket->max_per_person     = $request->get('max_per_person');
        $ticket->description        = $request->get('description');

        $ticket->save();

        session()->flash('message', 'Successfully Created Ticket');

        return response()->json([
            'status'      => 'success',
            'id'          => $ticket->id,
            'message'     => 'Refreshing...',
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Pause ticket / take it off sale
     *
     * @param Request $request
     * @return mixed
     */
    public function postPauseTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        $ticket->is_paused = ($ticket->is_paused == 1) ? 0 : 1;

        if ($ticket->save()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Ticket Successfully Updated',
                'id'      => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to pause/resume', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => 'Whoops! Looks like something went wrong. Please try again.',
        ]);
    }

    /**
     * Deleted a ticket
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDeleteTicket(Request $request)
    {
        $ticket_id = $request->get('ticket_id');

        $ticket = Ticket::scope()->find($ticket_id);

        /*
         * Don't allow deletion of tickets which have been sold already.
         */
        if ($ticket->quantity_sold > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Sorry, you can\'t delete this ticket as some have already been sold',
                'id'      => $ticket->id,
            ]);
        }

        if ($ticket->delete()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Ticket Successfully Deleted',
                'id'      => $ticket->id,
            ]);
        }

        Log::error('Ticket Failed to delete', [
            'ticket' => $ticket,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $ticket->id,
            'message' => 'Whoops! Looks like something went wrong. Please try again.',
        ]);
    }

    /**
     * Edit a ticket
     *
     * @param Request $request
     * @param $event_id
     * @param $ticket_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function postEditTicket(Request $request, $event_id, $ticket_id)
    {
        $ticket = Ticket::scope()->findOrFail($ticket_id);

        /*
         * Override some validation rules
         */
        $validation_rules['quantity_available'] = ['integer', 'min:'.($ticket->quantity_sold + $ticket->quantity_reserved)];
        $validation_messages['quantity_available.min'] = 'Quantity available can\'t be less the amount sold or reserved.';

        $ticket->rules = $validation_rules + $ticket->rules;
        $ticket->messages = $validation_messages + $ticket->messages;

        if (!$ticket->validate($request->all())) {
            return response()->json([
                'status'   => 'error',
                'messages' => $ticket->errors(),
            ]);
        }

        $ticket->title              = $request->get('title');
        $ticket->quantity_available = !$request->get('quantity_available') ? null : $request->get('quantity_available');
        $ticket->price              = $request->get('price');
        $ticket->start_sale_date    = $request->get('start_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('start_sale_date')) : null;
        $ticket->end_sale_date      = $request->get('end_sale_date') ? Carbon::createFromFormat('d-m-Y H:i', $request->get('end_sale_date')) : null;
        $ticket->description        = $request->get('description');
        $ticket->min_per_person     = $request->get('min_per_person');
        $ticket->max_per_person     = $request->get('max_per_person');

        $ticket->save();

        return response()->json([
            'status'      => 'success',
            'id'          => $ticket->id,
            'message'     => 'Refreshing...',
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    /**
     * Import ticket
     *
     * @param Request $request
     * @param $event_id
     * @return mixed
     */
    public function postImportTicket(Request $request, $event_id)
    {
        $rules = [
            'ticket_list' => 'required|mimes:csv,txt|max:5000|',
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status'   => 'error',
                'messages' => $validator->messages()->toArray(),
            ]);

        }

        $num_added = 0;
        if ($request->file('ticket_list')) {
            $path = $request->file('ticket_list')->getRealPath();

            $handle = fopen($path,"r");
            while ($data = fgetcsv($handle, 10000)) {
                $num_added++;
                $num = count($data);
                if($num!==1){
                    return response()->json([
                        'status'   => 'error',
                        'messages' => 'Format error',
                    ]);
                }
                /**
                 * Create the ticket
                 */
                $attendee = new Ticket();
                $attendee->event_id = $event_id;
                $attendee->card = $data[0];
                $attendee->state = 0;
                $attendee->account_id = Auth::user()->account_id;
                $attendee->is_tao = 0;
                $attendee->save();
            }
            fclose($handle);

        }

        session()->flash('message', $num_added . ' Attendees Successfully Invited');

        return response()->json([
            'status'      => 'success',
//            'id'          => $attendee->id,
            'redirectUrl' => route('showEventTickets', [
                'event_id' => $event_id,
            ]),
        ]);
    }
}
