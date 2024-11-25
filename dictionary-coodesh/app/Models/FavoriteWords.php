<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteWords extends Model
{
    /** @use HasFactory<\Database\Factories\FavoriteWordsFactory> */
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['word', 'user_id', 'added'];


}
