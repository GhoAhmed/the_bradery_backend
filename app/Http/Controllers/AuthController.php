<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        // Validation logic for registration data
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        // You can also generate a token and return it for auto-login
        $token = $user->createToken('authToken');
        $plainTextToken = $token->accessToken;

        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function signin(Request $request)
    {
        // Validation logic for login data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Attempt to log in the user
        if (Auth::attempt($request->only('email', 'password'))) {
            // Authentication was successful
            $user = Auth::user();
            $token = $user->createToken('authToken');
            $plainTextToken = $token->accessToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        } else {
            // Authentication failed
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
