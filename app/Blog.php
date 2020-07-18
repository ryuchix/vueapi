<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function getImageAttribute($value) {
        return $this->attributes['image'] = 'https://ragnarokmobile.net/uploads/images/blogs/'.$value;
    }

    public function getExcerptAttribute($value) {
        return $this->attributes['excerpt'] = str_replace(array("\n", "\t", "\r"), '', strip_tags($value));
    }
}
