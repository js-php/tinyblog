<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    protected $tag; 

    /**
     * Конструктор, начальная инициализация
     *
     * @param  \App\Tag $tag
     * @return void
     */
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    
    /**
     * Индексная страница
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = $this->tag->all();
        return view('blog.tag.index', compact('tags'));
    }

    /**
     * Список постов по тегу.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function PostList(Request $request, $slug)
    {
        $hash   = $this->tag->hash($slug);
        $searchArray = [[
            'hash' => $hash,
            'slug' => $slug,
        ]];
        $searchCollection   = collect($searchArray);
        $posts              = $this->tag->filterByManyHashSlug($searchCollection)->firstOrNew([])->posts;
        return view('blog.tag.postlist',compact('posts','tag'));
    }
}
