<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Order extends Model
{

    protected $fillable = [
        'title',
        'description',
        'body',
        'author'
    ];
}
