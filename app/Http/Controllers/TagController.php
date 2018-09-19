<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    protected $tag; 
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function index()
    {
        $tags = $this->tag->all();
        return view('blog.tag.index', compact('tags'));
    }

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
