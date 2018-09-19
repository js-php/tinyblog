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
     * устанавливает атррибуты hash и slug.
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

    /**
     * возвращает хеш строки
     *
     * @param  string  $str
     * @return string
     */
    public function hash($str): string
    {
        $str = trim($str);
        return (string) crc32($str);
    }

    /**
     * возвращает хеш строки
     *
     * @param  Illuminate\Support\Collection  $original
     * @param  Illuminate\Support\Collection  $given
     * @return Illuminate\Support\Collection
     */
    public function getModelsCollection(Array $tagNames): Collection
    {
        $tagModels = collect([]);
        foreach ($tagNames as $name) {
            $tagModels->push(new Tag(compact('name')));
        }
        return $tagModels;
    }

    /**
     * Сравнивает вложенные массивы в двух коллекциях по хешу
     *
     * @param  Illuminate\Support\Collection  $original
     * @param  Illuminate\Support\Collection  $given
     * @return Illuminate\Support\Collection
     */
    public function compareByHash(Collection $original, Collection $given): Collection
    {
        $originalHashes     = $original->pluck('hash');
        $givenHashes        = $given->pluck('hash');
        // берём хеши из оригинальной коллекции, которых нет в переданной
        $originalUniqHashes = $originalHashes->diff($givenHashes);
        // оставляем в коллекции только те массивы, которые имеют хеши, не присутствующие в переданной коллекции
        $filter = function($val) use ($originalUniqHashes) {
            return $originalUniqHashes->contains($val['hash']);
        };
        return $original->filter($filter);
    }

}
