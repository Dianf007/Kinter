<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Post;
use App\Models\Brand;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $classes = Course::latest()->take(3)->get();
        $latestPosts = Post::latest()->take(3)->get();
        $brands = Brand::all();
        $portfolioItems = Portfolio::all();

        return view('pages.home', compact(
            'classes',
            'latestPosts',
            'brands',
            'portfolioItems'
        ));
    }
}
