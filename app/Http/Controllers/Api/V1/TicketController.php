<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Http\Filters\V1\TicketFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Policies\V1\TicketPolicy;

class TicketController extends ApiController
{
    protected $policyClass = TicketPolicy::class;
    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        if ($this->isAble('store', Ticket::class)) {
            return new TicketResource(
                Ticket::create($request->mappedAttributes())
            );
        }

        return $this->errorResponse('You are not authorized to create a ticket', 403);
    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if($this->include('author')) {
                return new TicketResource($ticket->load('user'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->isAble('update', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

            return $this->errorResponse('You are not authorized to update that resource', 403);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if( $this->isAble('replace', $ticket)) {
                $ticket->update($request->mappedAttributes());
                return new TicketResource($ticket);
            }

            return $this->errorResponse('You are not authorized to replace that resource', 403);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($this->isAble('delete', $ticket)) {
                $ticket->delete();
                return $this->successResponse('Ticket deleted');
            }

            return $this->errorResponse('You are not authorized to delete that resource', 403);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        }
    }
}
