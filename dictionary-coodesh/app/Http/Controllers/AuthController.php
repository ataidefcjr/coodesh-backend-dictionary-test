<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            
            //Deleto os tokens anteriores
            $request->user()->tokens->each(function($token) {
                $token->delete();
            });

            return response()->json([
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'token' => $request->user()->createToken('user')->plainTextToken,
            ]);

            }else {
                return response()->json(['message' => 'Error message'],400);
        }
        
    }
}
