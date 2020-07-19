<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Blog extends Model
{
    public function getImageAttribute($value) {
        return $this->attributes['image'] = 'https://ragnarokmobile.net/uploads/images/blogs/'.$value;
    }

    public function getExcerptAttribute($value) {
        return $this->attributes['excerpt'] = str_replace(array("\n", "\t", "\r"), '', strip_tags($value));
    }

    public function getUpdatedAtAttribute($value) {
        $dt = date_create($value);
        return $this->attributes['updated_at'] = date_format($dt, 'Y-m-d\TH:i:sP');
    }
}
