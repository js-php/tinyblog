<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Support\Collection;
class TagRepo
{
    /**
     * Получает массив тегов из строки.
     *
     * @param  string
     * @return array
     */
    public function getTagsList($tagsInputString) : Array
    {
        $filteredTags       = [];
        $tagsInputString    = trim($tagsInputString);
        if (!$tagsInputString) return $filteredTags;
        // приводим допустимые разделители к базовому (,)
        $normalizedTags     = str_replace([':','.',';'],',', $tagsInputString); 
        $arTags             = explode(',', $normalizedTags);
        // убираем из списка тегов все пустые элементы, целиком состоящие из пробелов
        $filteredTags       = array_filter($arTags, 'trim');
        // у оставшихся тегов убираем по пробелы со сторон
        $filteredTags       = array_map('trim',$filteredTags);
        $filteredTags       = array_unique($filteredTags);
        return $filteredTags;
    }
}
