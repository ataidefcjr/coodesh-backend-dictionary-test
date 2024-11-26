<?php

namespace App\Http\Controllers;

use App\Models\FavoriteWords;
use App\Models\HistoryWords;
use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{
    //Faz o registro de um usuário
    public function register(Request $request)
    {
        //Validação dos dados
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => 'required|string|min:8|max:255'
        ]);

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


    //Mostra os dados do usuário 
    public function show(Request $request)
    {
        $user = $request->user();
        //Busca as 4 últimas palavras pesquisadas
        $userHistory = HistoryWords::select('word','added')->where('user_id', $user->id)->orderBy('added','desc')->take(4)->get();
        //Busca as 4 últimas palavras favoritadas
        $userFavorites = FavoriteWords::select('word','added')->where('user_id', $user->id)->orderBy('added','desc')->take(4)->get();

        $userData = [
            'id'=>$user->id,
            'name'=>$user->name,
            'email'=>$user->email,
            'createdAt' => $user->created_at
        ];

        return response()->json([
            'userData'=>$userData,
            'userRecentHistory' => $userHistory,
            'userRecentFavorites' => $userFavorites
        ],200);
    }
}
