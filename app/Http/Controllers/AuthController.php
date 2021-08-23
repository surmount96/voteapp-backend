<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function LoginStart(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $password = Hash::make('pass');

        if(User::where('email', $request->email)->update(['password' => $password]))
        {
            return response()->json(['message' => 'Enter the Vote Code sent to your email']);
        }

        return response()->json(['message' => 'You are not included in this voting']);
        
    }
    
    public function LoginFinish(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:4',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
        ]);
    }
}
