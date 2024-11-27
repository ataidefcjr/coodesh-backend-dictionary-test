<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Register 
     * 
     * In this route you can register yourself.
     * 
     * @unauthenticated
     */
    public function register(Request $request)
    {

        //Validação dos dados
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => 'required|string|min:6|max:255'
        ]);

        if ($valid) {
            //Cria o registro
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            //Busca os dados do usuário
            $user = User::select('*')->where('email', $request->email)->first();

            //Cria um token
            $token = $user->createToken('user')->plainTextToken;


            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'token' => $token,
            ], 200);
        }
    }
    
    /**
     * Login to API
     * @unauthenticated
     */
    public function login(Request $request)
    {
        //Validação dos dados
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:255'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {

            //Deleto os tokens anteriores
            $request->user()->tokens()->delete();

            return response()->json([
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'token' => $request->user()->createToken('user')->plainTextToken,
            ]);
        }
        throw new Exception();
    }

    /**
     * Logout from API
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();
        DB::table('sessions')->where('user_id', $user->id)->delete();

        // A simple good bye
        return response()->json([
            'message' => 'Good bye '. $user->name,
        ], 200);
    }
}
