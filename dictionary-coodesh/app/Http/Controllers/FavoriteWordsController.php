<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\FavoriteWords;
use App\Models\Words;
use Exception;
use Illuminate\Http\Request;

class FavoriteWordsController extends Controller
{
    //Guarda um favorito
    public function store(Request $request)
    {
        $id = $request->user()->id;
        $word = $request->route('word');

        //Validação
        $isValidWord = Words::where('word', $word)->first();
        $alreadyFavorited = FavoriteWords::where('word', $word)->where('user_id', $id)->first();

        //Cria o registro
        if ($isValidWord && !$alreadyFavorited) {
            FavoriteWords::create([
                'word' => $word,
                'user_id' => $id,
                'added' => now()
            ]);
            return response()->json(['message' => "$word favorited"], 200);
        }

        //Retorna o erro padrão
        throw new Exception();
    }

    //Exclui um favorito
    public function destroy(Request $request)
    {
        $word = $request->route('word');
        $id = $request->user()->id;

        //Validação
        $favoritedWord = FavoriteWords::where('user_id', $id)->where('word', $word)->first();
        
        //Deleta do DB
        if ($favoritedWord) {
            $favoritedWord->delete();
            return response()->json(['message' => "$word unfavorited"], 200);
        }

        //Retorna erro padrão
        throw new Exception();
    }

    //Mostra os favoritos
    public function show(Request $request)
    {
        //Validação
        $request->validate([
            'limit' => 'nullable|numeric|max:100|min:1',
            'page' => 'nullable|numeric|min:1',
        ]);
        
        //Valores padrões
        $id = $request->user()->id;
        $limit = $request->get('limit', 4);
        $page = $request->get('page', 1);

        //Busca a query no DB
        $result = FavoriteWords::where('user_id', $id)->select('*')->paginate($limit, ['*'], ['page'], $page);

        //Formatação 
        $formatedResult = WordsResource::responseFormatter($result);

        return response()->json($formatedResult,200);
    }
}
