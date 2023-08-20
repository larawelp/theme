<?php

namespace App\Http\Controllers\Generic;

use App\Http\Controllers\Controller;
use App\Models\Wp\Post\Post;

class Page extends Controller
{
    /**
     * Page constructor.
     */
    public function __construct()
    {
        if (! empty($GLOBALS['post'])) {
            setup_postdata($GLOBALS['post']);
        }
    }

    public function index()
    {
        $frontpage_id = get_option('page_on_front');

        if (get_post()->ID == $frontpage_id) {
            return $this->view('generic.home');
        }
        $post = new Post();
        $data = [
            'post' => $post,
            'model' => \Corcel\Model\Post::find($post->ID)
        ];


        return $this->view('generic.page', $data);
    }
}
