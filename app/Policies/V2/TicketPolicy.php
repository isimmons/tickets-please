<?php

namespace App\Policies\V2;

use App\Models\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Ticket $ticket): bool
    {
        return $user->id === $ticket->user_id;
    }
}
