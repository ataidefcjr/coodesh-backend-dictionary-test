<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        //Validação dos dados
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            //Deleto os tokens anteriores
            $request->user()->tokens->each(function ($token) {
                $token->delete();
            });

            return response()->json([
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'token' => $request->user()->createToken('user')->plainTextToken,
            ]);
        } 
        throw new Exception();
        
    }
}
