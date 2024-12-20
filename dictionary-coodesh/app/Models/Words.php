<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Words extends Model
{
    /** @use HasFactory<\Database\Factories\WordsFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['word'];
}
