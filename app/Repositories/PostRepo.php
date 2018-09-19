<?php

namespace App\Repositories;

use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Validator;

class PostRepo
{
    /**
     * Возвращает массив c вложенными массивами-полями для сохранения новых тегов.
     *
     * @param  Array
     * @return Array
     */
    public function getNewTagFieldsList(Array $sendedTagList, \Illuminate\Support\Collection $existTagRows) : Array
    {
        // $newTagsRows    = [];
        // if (!$sendedTagList) return $newTagsRows;

        $existTagList   = $existTagRows->pluck('name')->toArray();
        $newTagList     = array_diff($sendedTagList, $existTagList);
        $dateNow        = (new \DateTime)->format('Y-m-d H:i:s');
        $dbRowsFill     = function($a)
        use ($dateNow)
        { 
            return ['name' => $a, 'created_at' => $dateNow, 'updated_at' => $dateNow];
        };
        $newTagsRows    = array_map($dbRowsFill, $newTagList);
        return $newTagsRows;
    }

    public function saveAndAttachNewTags(Array $newTagsRows, Post $blogpost)
    {
        if (!$newTagsRows) return false;
        $newTagsRows    = collect($newTagsRows);
        $allNewTags     = [];
        $newTagsRows->each(function($elm) use (&$allNewTags) {
            $allNewTags[] = new Tag($elm);
        });
        $res = $blogpost->tags()->saveMany($allNewTags);
        return $res;
    }

    public function endRedirect()
    {
        if (request()->has('save_and_edit')) {
            return back();
        }
        return redirect()->route('blogpost.index');
    }

    /**
     * Получает массив тегов из строки.
     *
     * @param  string
     * @return array
     */
    // public function getTagsList($tagsInputString) : Array
    // {
    //     $filteredTags       = [];
    //     $tagsInputString    = trim($tagsInputString);
    //     if (!$tagsInputString) return $filteredTags;
    //     // приводим допустимые разделители к базовому (,)
    //     $normalizedTags     = str_replace([':','.',';'],',', $tagsInputString); 
    //     $arTags             = explode(',', $normalizedTags);
    //     // убираем из списка тегов все пустые элементы, целиком состоящие из пробелов
    //     $filteredTags       = array_filter($arTags, 'trim');
    //     // у оставшихся тегов убираем по пробелы со сторон
    //     $filteredTags       = array_map('trim',$filteredTags);
    //     $filteredTags       = array_unique($filteredTags);
    //     return $filteredTags;
    // }

    // сделать контроль типов, переделать возвращаемый объект
    public function attachNewTags($sendedTagList,$createdPost)
    {
        $existTagRows = Tag::whereIn('name', $sendedTagList)->get();
        if ($existTagRows->count()) {
            $createdPost->tag()->attach($existTagRows->pluck('id'));
        }
        return $existTagRows;
    }

    // из строки - массив getTagsList
    // получить el-представление тех, что есть в базе getExist
    // получить массив новых отдельно
    // el приаттачить
    // новые записать и приаттачить
}
