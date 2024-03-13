<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;

class TicketController extends Controller
{
    /*
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with(['student', 'tutor', 'priority', 'course', 'ticketStatuses'])->get();
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
        return $ticket;
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
