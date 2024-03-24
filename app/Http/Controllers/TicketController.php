<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use Carbon\Carbon;

class TicketController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['student', 'tutor', 'priority', 'course', 'ticketStatuses', 'program', 'typeOfMachine', 'operatingSystem'])->get();
        return $tickets;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $body = $request->except('scheduled_start_time', 'scheduled_end_time');
        $body['scheduled_start_time'] = date($request->get('scheduled_start_time')); // Convert datetime to timestamp
        $body['scheduled_end_time'] = date($request->get('scheduled_end_time')); // Convert datetime to timestamp

        $ticket = Ticket::create($body);
        $ticket->ticketStatuses()->attach(1); // Newly created ticket has status "New" (id 1)
        $ticket->refresh();
        $ticket = Ticket::where('id', $ticket->id)->with(['student', 'tutor', 'priority', 'course', 'ticketStatuses', 'program', 'typeOfMachine', 'operatingSystem'])->first();
        return $ticket;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $ticket = Ticket::where('id', $id)->with(['student', 'tutor', 'priority', 'course', 'ticketStatuses', 'program', 'typeOfMachine', 'operatingSystem'])->first();
        return $ticket;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $body = $request->except('scheduled_start_time', 'scheduled_end_time');
        $body['scheduled_start_time'] = date($request->get('scheduled_start_time')); // Convert datetime to timestamp
        $body['scheduled_end_time'] = date($request->get('scheduled_end_time')); // Convert datetime to timestamp

        $ticket->priority_id = $body['priority_id'];
        $ticket->assigned_tutor_id = $body['tutor_id'];

        // If new status is different from current status, then update
        if ($ticket->latest_status->id != $body['ticket_status_id']) {
            $ticket->ticketStatuses()->attach($body['ticket_status_id']);

            // If status is moved to "In-progress", update actual_start_time
            if ($body['ticket_status_id'] == 3) {
                $ticket->actual_start_time = Carbon::now();
            }
            // If status is moved to "Resolved", update actual_end_time & move status to "Closed" immediately
            else if ($body['ticket_status_id'] == 4) {
                $ticket->actual_end_time = Carbon::now();
                $ticket->ticketStatuses()->attach(7); // Status "Close" (id 7)
            }
        }

        $ticket->scheduled_start_time = $body['scheduled_start_time'];
        $ticket->scheduled_end_time = $body['scheduled_end_time'];
        $ticket->tutor_note = $body['tutor_note'];

        $ticket->save();
        $ticket->refresh();

        $ticket = Ticket::where('id', $ticket->id)->with(['student', 'tutor', 'priority', 'course', 'ticketStatuses', 'program', 'typeOfMachine', 'operatingSystem'])->first();
        return $ticket;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}

// ErrorException: Object of class App\Models\TicketStatus could not be converted to int in file /Users/minhanhbui/fss-app/app/Http/Controllers/TicketController.php on line 74
