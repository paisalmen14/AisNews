<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\NewsCategory;


class NewsController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $news = News::query()->when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('content', 'like', "%{$search}%");

        })->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString();
    

        return view('pages.news.index', compact('news'));
    }

    public function show($slug)
    {
        $news = News::where('slug', $slug)->firstOrFail();
        $newest = News::orderBy('created_at', 'desc')->get()->take(5);

        return view('pages.news.show', compact('news', 'newest'));
    }

    public function category($slug)
    {
        $category = NewsCategory::where('slug', $slug)->firstOrFail();
        
        return view('pages.news.category', compact('category'));
        
    }
}
