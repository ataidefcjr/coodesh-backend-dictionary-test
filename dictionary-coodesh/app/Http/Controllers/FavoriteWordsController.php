<?php

namespace App\Http\Controllers;

use App\Http\Resources\WordsResource;
use App\Models\FavoriteWords;
use Illuminate\Http\Request;

class FavoriteWordsController extends Controller
{
    public function store(Request $request)
    {

        $userId = $request->get('id');
        $word = $request->route('word');

        try {
            FavoriteWords::firstOrCreate([
                'word' => $word,
                'user_id' => $userId,
            ], [
                'word' => $word,
                'user_id' => $userId,
                'added' => now()
            ]);
            return response()->json(['message' => "Favorited $word"],200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error message'], 400);
        }
    }

    public function destroy(Request $request)
    {
        $word = $request->route('word');
        $id = $request->get('id');

        try {
            $register = FavoriteWords::where('user_id', $id)->where('word', $word)->first();
            if ($register) {
                $register->delete();
                return response()->json(['message' => "Unfavorited $word"], 200);
            } else {
                return response()->json(['message' => 'Error message'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error message'], 400);
        }
    }

    public function show(Request $request){
        $id = $request->get('id');
        $limit = $request->get('limit', 4);
        $page = $request->get('page', 1);

        $result = FavoriteWords::where('user_id', $id)->select('word')->paginate($limit, ['*'],['page'],$page);

        $formatedResult = WordsResource::responseFormatter($result);

        return $formatedResult;
    }
}
