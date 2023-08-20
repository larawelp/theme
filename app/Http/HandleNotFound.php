<?php

namespace App\Http;

class HandleNotFound
{
    public function handle404()
    {
        $r = \Response::make(view('errors.404')->render(), 404);
        return $r;
    }

    public function handle404Terminate()
    {
        $r = \Response::make(view('errors.404')->render(), 404);
        $r->send();
    }
}
