<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\Words;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WordsController extends Controller
{
    public function show(Request $request)
    {
        $userId = $request->route('id');
        $word = $request->route('word');
        $wordRecord = Words::where('word', '=', $word)->first();
        $url = "https://api.dictionaryapi.dev/api/v2/entries/en/$word";
        $response = Http::get($url);

        if (!isset($response['message'])) {
            //Passar ID do usuario 
            $userId = 'f869da79-b7b2-4ea0-98f3-1a7408dc7cfe';
            try {
                //Se por acaso não existir a palavra no DB mas existir na API, registra a palavra no DB
                //Criei esta condicional pois por exemplo a palavra 'water' não existia no DB 
                if (!$wordRecord){
                    Words::create(['word'=>$word]);
                }

                //Registrar no histórico do usuário
                HistoryWordsController::store($userId, $word);
                //Retorna o resultado
                return response()->json($response->json(), 200);
            } catch (\Exception $e) {
                //Se houer algum erro, retorna a mensagem padrão
                
                return response()->json(['message' => "$e"], 400);
            }
        } else {
            //Se a palavra não existir no banco OU se não existir na API retorna o erro padrão
            return response()->json(['message' => "Error message"], 400);
        }
    }

    //Busca de palavras com paginação
    public function index(Request $request)
    {
        $search = $request->get('search' ,'');
        $limit = $request->get('limit' ,4);
        $page = $request->get('page' ,1);
        
        try {
            //Busca no Banco de Dados
            $result = Words::where('word', 'like', "$search%")->select('word')->paginate($limit, ['*'], ['page'], $page);
            //Formata o resultado
            $formatedResult = WordsResource::searchResponseFormatter($result);
            //Retorna o resultado
            return response()->json($formatedResult, 200);
        } catch (\Exception $e){
            //Se houer algum erro, retorna a mensagem padrão
            return response()->json(['message' => 'Error Message'], 400);
        }
    }
}
