<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\HistoryWords;
use Illuminate\Http\Request;

class HistoryWordsController extends Controller
{
    static public function store($userId, $word){
        HistoryWords::firstOrCreate([
            'user_id' => $userId,
            'word' => $word,
        ],[
            'user_id' => $userId,
            'word' => $word,
            'added' => now()
        ]);
    }

    static public function index($userId, $page, $limit){
        
        $data = HistoryWords::where('user_id', $userId)->select('*')->paginate($limit, ['*'], ['page'], $page);
        $formatedHistory = WordsResource::responseFormatter($data);
        return $formatedHistory;
    }


}
