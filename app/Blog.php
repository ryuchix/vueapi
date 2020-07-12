<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function getImageAttribute($value) {
        return $this->attributes['image'] = 'https://ragnarokmobile.net/uploads/images/blogs/'.$value;
    }
}
