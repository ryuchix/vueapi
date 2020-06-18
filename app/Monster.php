<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    public function maps() {
        return $this->belongsToMany('App\Map');
    }

    public function items() {
        return $this->belongsToMany('App\Item');
    }

}
