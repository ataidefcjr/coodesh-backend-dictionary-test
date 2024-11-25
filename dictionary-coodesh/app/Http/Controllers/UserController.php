<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Guid\Guid;

class UserController extends Controller
{

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:' . User::class,
                'password' => 'required|string|min:8|max:255'
            ]);
        } catch (ValidationException $e) {
            return response()->json(["message" => "Error message"], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken('user')->plainTextToken;
        $user_id = User::select('id')->where('email', $request->email)->first();

        return response()->json([
            'id' => $user_id->id,
            'name' => $user->name,
            'token' => $token,
        ], 200);
    }



    public function show(Request $request)
    {
        return response()->json([
            $request->user(),
        ]);
    }

}
