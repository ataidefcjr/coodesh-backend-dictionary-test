<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryWords extends Model
{
    /** @use HasFactory<\Database\Factories\HistoryWordsFactory> */
    use HasFactory;


    public $timestamps = false;
    protected $fillable = ['word', 'user_id', 'added'];

}
