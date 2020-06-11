<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'key_id', 'SellPrice', 'Icon',
    ];
}
