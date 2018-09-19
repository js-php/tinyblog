<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $fillable = ['body', 'happened_at']; 

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Сохраняет пост блога
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Post
     */
    public function saveNew($request)
    {
        $postData = $request->only($this->fillable);
        return static::create($postData);
    }
}
