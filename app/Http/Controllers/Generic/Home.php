<?php

namespace App\Http\Controllers\Generic;

use App\Http\Controllers\Controller;

class Home extends Controller
{
    public function index()
    {
        $data = [];

        return \View::make('generic.home', $data);
    }

    public function blog()
    {
        $data = [];
        return \View::make('generic.blog', $data);
    }
}
