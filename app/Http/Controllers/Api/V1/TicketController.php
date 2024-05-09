<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Http\Filters\V1\TicketFilter;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
        try {
            User::findOrFail($request->input('data.relationships.author.data.id'));

            $this->isAble('store', null);

            return new TicketResource(Ticket::create($request->mappedAttributes()));
        } catch (ModelNotFoundException $exception) {
            return $this->successResponse('User not found', [
                'error' => 'The Provided user id does not exist.'
            ]);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('You are not authorized to create a ticket', 403);
        }
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

            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('You are not authorized to update that resource', 403);
        }
    }

    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('replace', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);

        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('You are not authorized to replace that resource', 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            $this->isAble('delete', $ticket);

            $ticket->delete();

            return $this->successResponse('Ticket deleted');
        } catch (ModelNotFoundException $exception) {
            return $this->errorResponse('Ticket not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->errorResponse('You are not authorized to delete that resource', 403);
        }
    }
}
