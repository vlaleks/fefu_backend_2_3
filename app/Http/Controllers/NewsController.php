<?php

namespace App\Http\Controllers;

use \App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getList() 
    {
        $news_list = News::query()
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(5);

        return view('news_list', ['news_list' => $news_list]);
    }

    public function getDetails(string $slug)
    {
        $news_item = News::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->where('published_at', '<=', now())
            ->first();

        if ($news_item === null)
            return abort(404);

        return view('news_item', ['item' => $news_item]);
    }
}