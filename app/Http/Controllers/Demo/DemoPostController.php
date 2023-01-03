<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class DemoPostController extends Controller
{
    public function index()
    {
        $posts = new Post;
        $data = Post::all();
        $result = $posts->recursive($data, 0);
        return $result;
    }

    public function create(Request $request)
    {
        $result = $this->index();

        $data = Post::all();

        $posts = new Post;
        // return $posts->render_menu($data);
        return view('demo.post.create', compact('result'));
    }

}
