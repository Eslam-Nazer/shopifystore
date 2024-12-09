<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterReguest;

class UserAuthController extends Controller
{
    use HttpResponse;

    /**
     * Summary of login
     * @param \App\Http\Requests\UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        if (! Auth::attempt(credentials: $credentials)) {
            return $this->errorResponse('', null, 501);
        }

        $user = User::where(['email' => $request->email])->first();

        return $this->successResponse([
            'user'      => $user,
            'token'     => $user->createToken("Api Token {$user->name}")->plainTextToken,
        ], null, 200);
    }

    /**
     * Summary of register
     * @param \App\Http\Requests\UserRegisterReguest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRegisterReguest $request): JsonResponse
    {
        $request->validated();

        $user = User::create([
            "name"  => $request->name,
            "email"     => $request->email,
            "password"  => Hash::make($request->password)
        ]);

        return response()->json([
            'message'       => "User created successfully please check your email",
            'token'         => $user->createToken("Token")->plainTextToken
        ]);
    }
}
