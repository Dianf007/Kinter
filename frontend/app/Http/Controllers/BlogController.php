<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::with(['categories','tags','author'])
                     ->latest()
                     ->paginate(6);

        $categories = \App\Models\Category::all();
        $tags = \App\Models\Tag::all();
        $popularPosts = Blog::orderBy('comment_count','desc')->take(3)->get();

        return view('pages.blog', compact(
            'posts',
            'categories',
            'tags',
            'popularPosts'
        ));
    }
}
