<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\Words;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WordsController extends Controller
{

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
        if ($cachedResponse){
            $formatedTime = number_format(($cachedEndTime - $cachedStartTime)*1000,4) . 'ms';
            return response()->json($cachedResponse, 200)->withHeaders([
                'x-cache'=>'HIT',
                'x-response-time'=> $formatedTime
            ]);
        }

        //Calcular tempo de chamada a API externa
        $startTime = microtime(true);
        $response = Http::get($url)->json();
        $endTime = microtime(true);

        if (!isset($response['message'])) {
            try {
                //Registrar no histórico do usuário
                HistoryWordsController::store($userId, $word);

                //Guarda em Cache
                $cacheTime = 30*24*60*60; // 30dias, 24horas, 60minutos, 60segundos
                Cache::put("api-dictionary-$word",$response,$cacheTime);

                //Retorna a Resposta
                $formatedTime = number_format(($endTime - $startTime)*1000,4) . 'ms';
                return response()->json($response, 200)->withHeaders([
                    'x-cache'=>'MISS',
                    'x-response-time'=> $formatedTime
                ]);

            } catch (\Exception $e) {
                //Se houer algum erro, retorna a mensagem padrão
                return response()->json(['message' => "Error message"], 400);
            }
        } 
        return response()->json(['message' => "Error message"], 400);
    }



    //Busca de palavras com paginação
    public function index(Request $request)
    {
        //Valores padrões de acordo com o README fornecido
        $search = $request->get('search', '');
        $limit = $request->get('limit', 4);
        $page = $request->get('page', 1);

        try {
            //Busca no Banco de Dados
            $result = Words::where('word', 'like', "$search%")->select('word')->paginate($limit, ['*'], ['page'], $page);
            //Formata o resultado
            $formatedResult = WordsResource::searchResponseFormatter($result);

            //Retorna o resultado
            return response()->json($formatedResult, 200);

        } catch (\Exception $e) {

            //Se houer algum erro, retorna a mensagem padrão
            return response()->json(['message' => 'Error Message'], 400);
        }
    }
}
