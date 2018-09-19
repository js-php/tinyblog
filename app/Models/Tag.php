<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
class Tag extends Model
{
    protected $fillable = ['name','hash','slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function insertItems(Array $tagNames)
    {
        // foreach ($tagNames as $tagName) {
        //     $tagName = 
        // }

        // $tagNamesCollection->each($iterationFunct);
        // $tagNames
        // $fields['hash'] = crc32($fields['name']);
    }

    public function scopeFilterByManyHashSlug($query, Collection $tagNames)
    {
        foreach ($tagNames as $nameCollection) {
            if (!($nameCollection['hash'] && $nameCollection['slug'])) continue;
            $query->orWhere([
                ['hash', $nameCollection['hash']],
                ['slug', $nameCollection['slug']]
            ]);
        }
        return $query;
    }

    /**
     * устанавливает hash.
     *
     * @param  string  $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $slug                       = str_slug($value);
        $hash                       = $this->hash($slug);
        $this->attributes['name']   = $value;
        $this->attributes['hash']   = $hash;
        $this->attributes['slug']   = $slug;
    }

    public function hash($str): string
    {
        $str = trim($str);
        return (string) crc32($str);
    }

    public function getModelsCollection(Array $tagNames): Collection
    {
        $tagModels = collect([]);
        foreach ($tagNames as $name) {
            $tagModels->push(new Tag(compact('name')));
        }
        return $tagModels;
    }

    public function compareByHash(Collection $original, Collection $given): Collection
    {
        $originalHashes = $original->pluck('hash');
        $givenHashes    = $given->pluck('hash');
        $res            = $originalHashes->diff($givenHashes);
        $filter = function($val) use ($res) {
            return $res->contains($val['hash']);
        };
        return $original->filter($filter);
    }

}
