<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\MfaTokenNotification;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return $this->sendMfaToken($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return $this->sendMfaToken(Auth::user());
    }

    protected function sendMfaToken($user)
    {
        $token = rand(1000, 9999);
        $user->notify(new MfaTokenNotification($token));

        return response()->json([
            'message' => 'MFA token sent',
            'data' => [
                'user_id' => $user->id,
                'token_expires_in' => 300 // 5 minutes
            ]
        ]);
    }

    public function verifyMfa(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mfa_token' => 'required|numeric|digits:4'
        ]);

        $user = User::find($request->user_id);

        // In a real app, verify against stored token in cache/database
        if ($request->mfa_token == 1234) { // Simplified for example
            $token = $user->createToken('mobile-app')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]);
        }

        return response()->json([
            'message' => 'Invalid MFA token'
        ], 401);
    }

    public function resendMfa(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        return $this->sendMfaToken(User::find($request->user_id));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}