<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::select('users.*')
                ->join('tickets', 'tickets.user_id', '=', 'users.id')
                ->filter($filters)
                ->distinct()
                ->paginate()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author)
    {
        if($this->include('tickets')) {
            return new UserResource($author->load(['tickets']));
        }

        return new UserResource($author);
    }
}
