<?php

use App\Http\Controllers\FavoriteWordsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return response()->json([
        "message"=> "Fullstack Challenge 🏅 - Dictionary"
    ],200);
});

Route::post('/auth/signup', function() {
    return response()->json(['message'=>'auth/signup route']);
});

Route::post('/auth/signin', function() {
    return response()->json(['message'=>'auth/signin route']);
});

Route::post('/entries/en/{word}', [WordsController::class, 'show']);

Route::get('/entries/en', [WordsController::class, 'index']);

Route::get('/user/me/history', [UserController::class, 'viewHistory']);

Route::get('/user/me/favorites', [FavoriteWordsController::class, 'show']); 

Route::post('/entries/en/{word}/favorite', [FavoriteWordsController::class, 'store']);

Route::delete('/entries/en/{word}/unfavorite', [FavoriteWordsController::class, 'destroy']);

