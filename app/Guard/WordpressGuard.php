<?php

namespace App\Guard;

use App\Models\WordpressUser;
use Illuminate\Auth\RequestGuard;
use Illuminate\Auth\SessionGuard;

class WordpressGuard extends RequestGuard
{
    public function logout()
    {
        \Cookie::forget('blog_token');
    }

    public function loginUsingId($id, $remember = false)
    {
        $user = \App\Models\WordpressUser::find($id);
        if ($user) {
            $this->setUser($user);
            return true;
        }
        return false;
    }

    public function loginUsingWordpressUser(null|\WP_User $user)
    {
        if($user) {
            $model = new WordpressUser((array) $user->data);
            $model->ID = $user->ID;
            $this->setUser($model);

            return true;
        }
    }
}
