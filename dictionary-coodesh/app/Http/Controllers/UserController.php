<?php

namespace App\Http\Controllers;

use App\Models\FavoriteWords;
use App\Models\HistoryWords;
use Illuminate\Http\Request;


class UserController extends Controller
{


    /**
     * Show User Data 
     * 
     * View the user's <strong>ID</strong>, <strong>name</strong>, <strong>email</strong>, <strong>creation date</strong>, the last 4 <strong>history entries</strong>, and their <strong>favorite words</strong>.
     * 
     */
    public function show(Request $request)
    {
        $user = $request->user();
        //Busca as 4 últimas palavras pesquisadas
        $userHistory = HistoryWords::select('word', 'added')->where('user_id', $user->id)->orderBy('added', 'desc')->take(4)->get();
        //Busca as 4 últimas palavras favoritadas
        $userFavorites = FavoriteWords::select('word', 'added')->where('user_id', $user->id)->orderBy('added', 'desc')->take(4)->get();

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'createdAt' => $user->created_at
        ];

        return response()->json([
            'userData' => $userData,
            /** @var object[]   */
            'userRecentHistory' => $userHistory,
            /** @var object[]  */
            'userRecentFavorites' => $userFavorites
        ], 200);
    }

    /**
     * Hello Message
     * 
     * This is a simple greeting.
     * @unauthenticated
     */
    public function index()
    {
        return response()->json(["message" => "Fullstack Challenge 🏅 - Dictionary"], 200);
    }
}
