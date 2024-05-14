<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Http\Filters\V1\TicketFilter;
use App\Policies\V1\TicketPolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends ApiController
{
    protected string $policyClass = TicketPolicy::class;

    /**
     * Returns all tickets as a TicketResource collection.
     * ```
     * queryParam - sort string - Data field(s) to sort by.
     * Separate multiple fields with commas, Denote descending sort with a minus sign.
     * Example: ?sort=title,-createdAt
     *
     * queryParam - filter[field]=value
     * status: Filter by status. Comma separated A,C,H,X
     * title: Filter by title. Exact or wildcard supported
     * createdAt,updatedAt: Filter by date or date range
     * Example: ?filter[status]=C,X&filter[title]=*fix*&filter[createdAt]=05-04-2023,01-02-2024
     * ```
     *
     * @param TicketFilter $filters
     * @return AnonymousResourceCollection
     * @see TicketResource
     * @see TicketFilter
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }


    /**
     * Store a newly created ticket.
     *
     * @param StoreTicketRequest $request
     * @return TicketResource|JsonResponse
     */
    public function store(StoreTicketRequest $request): TicketResource|JsonResponse
    {
        if ($this->isAble('store', Ticket::class)) {
            return new TicketResource(
                Ticket::create($request->mappedAttributes())
            );
        }

        return $this->errorResponse('You are not authorized to create a ticket', 403);
    }

    /**
     * Display the requested ticket.
     *
     * @param Ticket $ticket
     * @return TicketResource
     */
    public function show(Ticket $ticket): TicketResource
    {
        if($this->include('author')) {
            return new TicketResource($ticket->load('user'));
        }

        return new TicketResource($ticket);
    }


    /**
     * Update the specified ticket.
     *
     * @param UpdateTicketRequest $request
     * @param Ticket $ticket
     * @return TicketResource|JsonResponse
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): TicketResource|JsonResponse
    {
        if ($this->isAble('update', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }

        return $this->notAuthorized('You are not authorized to update that resource');

    }

    /**
     * Replace the specified ticket.
     *
     * @param ReplaceTicketRequest $request
     * @param Ticket $ticket
     * @return TicketResource|JsonResponse
     */
    public function replace(ReplaceTicketRequest $request, Ticket $ticket): TicketResource|JsonResponse
    {
        if( $this->isAble('replace', $ticket)) {
            $ticket->update($request->mappedAttributes());
            return new TicketResource($ticket);
        }

        return $this->notAuthorized('You are not authorized to replace that resource');

    }

    /**
     * Delete the specified ticket.
     *
     * @param Ticket $ticket
     * @return JsonResponse
     */
    public function destroy(Ticket $ticket): JsonResponse
    {
        if ($this->isAble('delete', $ticket)) {
            $ticket->delete();
            return $this->successResponse('Ticket deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource');

    }
}
