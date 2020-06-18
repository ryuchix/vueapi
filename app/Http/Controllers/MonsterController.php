<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Monster;

class MonsterController extends Controller
{
    public function index() {
        $monsters = Monster::paginate();

        return $monsters;
    }

    public function getMonster($id) {
        return Monster::find($id);
    }
}
