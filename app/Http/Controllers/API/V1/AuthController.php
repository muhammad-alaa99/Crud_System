<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());
        return responder()->success(['message' => 'user registered successfully'])->respond(Response::HTTP_CREATED);
    }


    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken("auth_token-{$user->email}")->plainTextToken;
        $data = [
            'token' => $token,
            'user' => $user,
        ];
        return responder()->success($data)->respond(Response::HTTP_OK);
    }

    public function updateProfile(UpdateProfileRequest  $request)
    {
        $user = auth('sanctum')->user();
        User::findOrFail($user->id)->update($request->validated());
        return responder()->success(['message' => 'profile updated successfully'])->respond(Response::HTTP_OK);
    }

    public function logout()
    {
        $user = auth('sanctum')->user();
        $user->tokens()->delete();

        return responder()->success(['message' => 'Logged out successfully'])->respond(Response::HTTP_OK);
    }
  
}
