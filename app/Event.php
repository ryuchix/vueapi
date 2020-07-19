<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['et', 'etmini', 'vr', 'boc', 'weeklies'];
}
