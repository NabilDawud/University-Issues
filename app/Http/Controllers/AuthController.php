<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('cms.auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember_me');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'icon' => 'error',
                'message' => 'Invalid credentials.'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'icon' => 'error',
                'message' => 'This account is inactive! Please contact the admin.'
            ], 403);
        }

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return response()->json([
            'icon' => 'success',
            'message' => 'Login successful.',
            'redirect' => route('admin.dashboard')
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login'))->with('success', 'Logged out successfully.');
    }
}
