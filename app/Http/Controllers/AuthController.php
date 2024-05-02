<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Authenticate a registered user
     * @param ApiLoginRequest $request
     * @return JsonResponse
     */
    public function login(ApiLoginRequest $request): JsonResponse
    {
        return $this->ok($request->get('email'));
    }

    public function register()
    {
        return $this->ok('registered');
    }


}
