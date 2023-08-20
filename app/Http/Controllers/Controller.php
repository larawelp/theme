<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use LaraWelP\Foundation\Routing\Traits\ViewDebugger as TraitsViewDebugger;
use LaraWelP\Foundation\Routing\Traits\ViewResolver;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, TraitsViewDebugger, ViewResolver;
}
