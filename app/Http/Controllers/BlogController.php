<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;

class BlogController extends Controller
{
    public function index() {
        return Blog::paginate();
    }

    public function getBlog($slug) {
        return Blog::where('slug', $slug)->first();
    }
}
