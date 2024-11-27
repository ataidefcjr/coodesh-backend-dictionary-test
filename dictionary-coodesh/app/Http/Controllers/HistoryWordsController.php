<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\HistoryWords;
use Illuminate\Http\Request;

class HistoryWordsController extends Controller
{
    //Guarda um histórico (método estático, chamado pelo WordController)
    static public function store($userId, $word)
    {
        //Verifica se existe o registro antes de criar
        HistoryWords::firstOrCreate([
            'user_id' => $userId,
            'word' => $word,
        ], [
            'user_id' => $userId,
            'word' => $word,
            'added' => now()
        ]);
    }

    /**
     * Word History
     * 
     * View all the words you've seen.
     * <br>
     * You can specify the number of results per page (limit) and the page you'd like to visit.
     */
    public function show(Request $request)
    {
        //Validação
        $request->validate([
            'limit' => 'nullable|numeric|max:100|min:1',
            'page' => 'nullable|numeric|min:1',
        ]);

        $userId = $request->user()->id;
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 4);

        //Busca resultado
        $data = HistoryWords::where('user_id', $userId)->select('*')->paginate($limit, ['*'], ['page'], $page);
        //Formata
        $formatedHistory = WordsResource::responseFormatter($data);

        return response()->json($formatedHistory, 200);
    }
}
