<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        return $request->all();
    }

    /**
     * Summary of authenticate
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request): JsonResponse
    {
        try {
            $credentials = $request->validate(rules: [
                'email'         => ['required', 'email'],
                'password'      => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email'     => ['The provided credentials are incorrect.']
                ]);
            }

            if (Auth::attempt($credentials)) {
                return response()->json(data: [
                    'user'  => Auth::user(),
                    'token' => $user->createToken("API Token")->plainTextToken,
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'errors'        => [
                    $th->getMessage()
                ]
            ]);
        }
    }

    public function sginup()
    {
        return response(json_encode(["resgister" => 'RegisterView']));
    }

    /**
     * s
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
