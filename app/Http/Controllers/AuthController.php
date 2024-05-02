<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * @return JsonResponse
     */
    public function login()
    {
        return $this->ok('Welcome to the API!');
    }
}
