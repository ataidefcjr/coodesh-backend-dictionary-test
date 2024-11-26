<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class WordsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    static public function searchResponseFormatter($result): array
    {
        $results = $result->pluck('word')->all();

        return [
            'results' => $results,
            'totalDocs' => $result->total(),
            'page' => $result->currentPage(),
            'totalPages' => $result->lastPage(),
            'hasNext' => $result->hasMorePages(),
            'hasPrev' => $result->currentPage() > 1
        ];
    }


    static public function responseFormatter($result){


        return [
            'results' => $result->map(fn($register)=>['word'=>$register->word, 'added'=>$register->added]),
            'totalDocs' => $result->total(),
            'page' => $result->currentPage(),
            'totalPages' => $result->lastPage(),
            'hasNext' => $result->hasMorePages(),
            'hasPrev' => $result->currentPage() > 1
        ];
    }
}
