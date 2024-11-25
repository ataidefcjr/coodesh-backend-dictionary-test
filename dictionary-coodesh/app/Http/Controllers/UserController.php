<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{

    public function signUp()
    {
        //
    }


    public function signIn()
    {
        //
    }

    public function viewHistory(Request $request){
        
        $userId = $request->get('id');
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 4);
        $userId = 'f869da79-b7b2-4ea0-98f3-1a7408dc7cfe';
        
        return response()->json(HistoryWordsController::index($userId, $page, $limit), 200);
    }
}
