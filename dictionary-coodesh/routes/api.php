<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteWordsController;
use App\Http\Controllers\HistoryWordsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/signup', function() {
    return response()->json(['message'=>'auth/signup route']);
});

Route::post('/auth/signin', [AuthController::class, 'login']);

Route::get('/', function () {
    return response()->json([
        "message"=> "Fullstack Challenge 🏅 - Dictionary"
    ],200);
});

Route::middleware('auth:sanctum')->group(function() {

    Route::post('/entries/en/{word}', [WordsController::class, 'show']);
    
    Route::get('/entries/en', [WordsController::class, 'index']);

    Route::get('/user/me', function(Request $request) { 
        return response()->json($request->user());
    });
    
    Route::get('/user/me/history', [HistoryWordsController::class, 'show']);
    
    Route::get('/user/me/favorites', [FavoriteWordsController::class, 'show']); 
    
    Route::post('/entries/en/{word}/favorite', [FavoriteWordsController::class, 'store']);
    
    Route::delete('/entries/en/{word}/unfavorite', [FavoriteWordsController::class, 'destroy']);

});




