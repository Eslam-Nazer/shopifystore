<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use Illuminate\Support\Facades\Validator;

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

    public function sginup()
    {
        return response(json_encode(["resgister" => 'RegisterView']));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function register(Request $request): JsonResponse
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'username'      => ['required', 'string', 'unique:users,name'],
                'email'         => ['required', 'string', 'email', 'unique:users,email'],
                'password'      => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed:confirmPassword'
                ],
            ]);

            if ($validateUser->fails()) {
                return response()->json(
                    ['errors'   => $validateUser->errors()],
                    http_response_code(401)
                );
            }

            $user = User::create([
                "name"  => $request->username,
                "email"     => $request->email,
                "password"  => Hash::make($request->password)
            ]);

            return response()->json([
                'message'       => "User created successfully please check your email",
                'token'         => $user->createToken("Token")->plainTextToken
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }
}
