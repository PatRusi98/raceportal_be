<?php

namespace App\Services;

use App\Models\User;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginService
{
    public function getMessage(string $name): string
    {
        return "Hello, $name!";
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;
            $expiresIn = config('sanctum.expiration');
            $expiryDate = Carbon::now()->addSeconds($expiresIn);
            $refreshToken = $user->createToken('authRefreshToken')->plainTextToken;
            $refreshExpiresIn = config('sanctum.refresh_expiration');
            $refreshExpiryDate = Carbon::now()->addSeconds($refreshExpiresIn);

            $actualUser = Users::query()->where('username', '=', $request->input('username'))->get();

            $response = [
                'token' => "Bearer $token",
                'expiration' => $expiryDate,
                'refreshToken' => "Refresh $refreshToken",
                'expirationRefresh' => $refreshExpiryDate,
                'user' => $user
                //'user' => $actualUser
            ];

            return response()->json($response, 200);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

//    public function refresh(Request $request)
//    {
//        $user = $request->user();
//        $user->tokens()->delete();
//        $token = $user->createToken('authToken')->plainTextToken;
//        $expiresIn = config('sanctum.expiration');
//        $expiryDate = Carbon::now()->addSeconds($expiresIn);
//        $refreshToken = $user->createToken('authRefreshToken')->plainTextToken;
//        $refreshExpiresIn = config('sanctum.refresh_expiration');
//        $refreshExpiryDate = Carbon::now()->addSeconds($refreshExpiresIn);
//
//        $response = [
//            'access_token' => $token,
//            'token_type' => 'Bearer',
//            'expires_in' => $expiresIn,
//            'expiry_date' => $expiryDate,
//            'refresh_token' => $refreshToken,
//            'refresh_expires_in' => $refreshExpiresIn,
//            'refresh_expiry_date' => $refreshExpiryDate,
//            'user' => $user
//        ];
//
//        return response()->json($response);
//    }

    public function logout() {
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Successful logout']);
    }

    public function signUp(Request $request) {
        $user = new Users();

        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token-type' => 'Bearer'
        ], 200);
    }
}
