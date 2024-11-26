<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\Words;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WordsController extends Controller
{

    //Dados da palavra
    public function show(Request $request)
    {
        $userId = $request->user()->id;
        $word = $request->route('word');
        $url = "https://api.dictionaryapi.dev/api/v2/entries/en/$word";

        //Lógica para calcular o tempo da busca no cache.
        $cachedStartTime = microtime(true);
        $cachedResponse = Cache::get("api-dictionary-$word");
        $cachedEndTime = microtime(true);

        //Resposta via cache.
        if ($cachedResponse) {
            HistoryWordsController::store($userId, $word);
            $formatedTime = number_format(($cachedEndTime - $cachedStartTime) * 1000, 4) . 'ms';
            return response()->json($cachedResponse, 200)->withHeaders([
                'x-cache' => 'HIT',
                'x-response-time' => $formatedTime
            ]);
        }

        //Calcular tempo de chamada a API externa
        $startTime = microtime(true);
        $response = Http::get($url)->json();
        $endTime = microtime(true);

        if (!isset($response['message'])) {

            //Se não existir a palavra no DB, registra para aparecer no endpoint /entries/en{search}
            Words::firstOrCreate(['word' => $word], ['word' => $word]);

            //Registrar no histórico do usuário
            HistoryWordsController::store($userId, $word);

            //Guarda em Cache
            $cacheTime = 30 * 24 * 60 * 60; // 30dias, 24horas, 60minutos, 60segundos
            Cache::put("api-dictionary-$word", $response, $cacheTime);

            //Retorna a Resposta
            $formatedTime = number_format(($endTime - $startTime) * 1000, 4) . 'ms';
            return response()->json($response, 200)->withHeaders([
                'x-cache' => 'MISS',
                'x-response-time' => $formatedTime
            ]);
        }
        throw new Exception();
        
    }



    //Busca de palavras do DB
    public function index(Request $request)
    {
        //Validação dos dados
        $request->validate([
            'search' => 'required|string|max:50',
            'limit' => 'nullable|numeric|min:1|max:100',
            'page' => 'nullable|numeric|min:1'
        ]);

        //Valores padrões de acordo com o README fornecido
        $search = $request->get('search');
        $limit = $request->get('limit', 4);
        $page = $request->get('page', 1);

        //Busca no Banco de Dados
        $result = Words::where('word', 'like', "$search%")->select('word')->paginate($limit, ['*'], ['page'], $page);

        //Formata o resultado
        $formatedResult = WordsResource::searchResponseFormatter($result);

        //Retorna o resultado
        return response()->json($formatedResult, 200);
    }
}
