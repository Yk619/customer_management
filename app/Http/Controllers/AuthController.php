<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\MfaTokenNotification;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Show dashboard
     */
    public function dashboard()
    {
        return view('auth.dashboard');
    }

    /**
     * New user registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Generate MFA token
        $token = rand(1000, 9999);
        $user->notify(new MfaTokenNotification($token));
        
        // Store in session
        $request->session()->put('mfa_user_id', $user->id);
        $request->session()->put('mfa_token', $token);

        return redirect()->route('verify-mfa')->with('status', 'Registration successful! Please check your email for the verification code.');
    }

    /**
     * Show MFA verification form
     */
    public function showMfaForm(Request $request)
    {
        if (!session()->has('mfa_token')) {
            return redirect('/login')->with('error', 'Session expired');
        }
        
        return view('auth.verify-mfa');
    }

    /**
     * Verify MFA token
     */
    public function verifyMfa1(Request $request)
    {
        $request->validate(['mfa_token' => 'required|numeric']);

        // Match token
        if ($request->mfa_token == $request->session()->get('mfa_token')) {
            $userId = $request->session()->get('mfa_user_id');
            $user = User::find($userId);
            
            Auth::login($user);
            $request->session()->forget(['mfa_token', 'mfa_user_id']);
            
            $token = $user->createToken('API Token')->accessToken;
            return redirect()->route('customers.index')->with('token', $token);
        }

        return back()->with('error', 'Invalid MFA token');
    }

    /**
     * Login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * User login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt user login
        if (Auth::attempt($credentials)) {
            $token = rand(1000, 9999);
            $request->user()->notify(new MfaTokenNotification($token));
            $request->session()->put('mfa_token', $token);
            return response()->json(['message' => 'MFA token sent']);
        }

        return back()->with('error', 'Invalid credentials');
    }

    /**
     * Verify token
     * */
    public function verifyMfa(Request $request)
    {
        $request->validate(['mfa_token' => 'required|numeric|digits:4']);
        
        if (!$request->session()->has('mfa_token')) {
            return redirect('/login')->with('error', 'Session expired');
        }
        if ($request->mfa_token == $request->session()->get('mfa_token')) {
            $userId = $request->session()->get('mfa_user_id');
            $user = User::findOrFail($userId);
            
            Auth::login($user);
            $request->session()->forget(['mfa_token', 'mfa_user_id']);
            
            return redirect()->intended(route('customers.index'));
        }

        return back()->withErrors(['mfa_token' => 'Invalid verification code']);
    }

    /**
     * User logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}