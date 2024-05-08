<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Permissions\V1\Abilities;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Authenticate a registered user
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $request->validated($request->all());

        if(!Auth::attempt($request->only(['email', 'password']))) {
            return $this->errorResponse('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);

        return $this->successResponse( 'Authenticated', [
            'token' => $user->createToken(
                'API token for' . $user->email,
                Abilities::getAbilities($user),
                now()->addMonth()
            )->plainTextToken,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->successResponse('');
    }


}
