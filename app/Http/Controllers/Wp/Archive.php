<?php

namespace App\Http\Controllers\Wp;

use App\Http\Controllers\Controller;
use App\Models\Wp\Post\Post;
use Corcel\Model;
use Illuminate\Http\Request;

class Archive extends Controller
{
    public function index()
    {
        $data = [];

        return $this->resolveView('generic.archive', $data);
    }
}
