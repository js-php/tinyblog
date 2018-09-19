<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\StringHelper;
use App\Http\Requests\PostFormRequest;

class PostController extends Controller
{
    protected $tag;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function __construct(Tag $tag)
    {
        // явно объявляем зависимости, которые будут использованы в классе
        $this->tag      = $tag;
    }

    public function index()
    {
        $posts  = Post::all();
        return view('blog.index', compact('posts'));
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags   = Tag::all()->pluck('name');
        return view('blog.create')->with(compact('tags'));        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Post  $post
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(PostFormRequest $request, Post $post)
    {
        // сразу сохраняем данные поста в базу, а затем занимаемся тегами
        $createdPost = $post->saveNew($request);
        
        $this->tagsSaveActions($createdPost);
        
        // если была нажата кнопка "сохранение и продолжение", редиректим на редактирование
        return $this->endRedirect();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::all()->pluck('name');
        $tagsString = implode(', ', $post->Tags->pluck('name')->toArray());
        return view('blog.edit', compact('post','tagsString','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post, Tag $tag)
    {
        $post->fill($request->only(['body']))->save();
        
        $this->tagsSaveActions($post);

        return $this->endRedirect();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    /**
     * Выполняет действия по сохранению тегов и прикрепления к посту
     *
     * @param  \App\Post  $post
     * @return void
     */

    protected function tagsSaveActions(Post $post)
    {
        // получаем массив тегов из строки формы создания поста
        $tagsLine   = request('tags') ?? '';
        $tagsList   = StringHelper::explode($tagsLine);
        if ($post && $tagsList) {
            // получаем коллекцию моделей всех тегов поста
            $postTags   = $this->tag->getModelsCollection($tagsList);
            
            // коллекция существующих в бд тегов и коллекция добавленных впервые
            $existTags  = $this->tag->filterByManyHashSlug($postTags)->get();
            $newTags    = $this->tag->compareByHash($postTags,$existTags);
            // прикрепляем (или открепляем) существующие теги к посту
            $post->tags()->sync($existTags->pluck('id'));
            
            // сохраняем с прикреплением к посту новые теги
            if ($newTags->count()) {
                $res = $post->tags()->saveMany($newTags);
            }
        }
    }

    /**
     * возвращает подходящий редирект
     *
     * @return void
     */

    protected function endRedirect()
    {
        if (request()->has('save_and_edit')) {
            return back();
        }
        return redirect()->route('posts.index');
    }
}
