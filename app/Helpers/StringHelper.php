<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Получает массив тегов из строки.
     *
     * @param  string
     * @return array
     */
    public static function explode(string $tagsInputString): Array
    {
        $separator          = ',';
        $filteredTags       = [];
        $tagsInputString    = trim($tagsInputString);
        if (!$tagsInputString) return $filteredTags;
        // приводим допустимые разделители к базовому (,)
        $normalizedStr      = str_replace([':','.',';'], $separator, $tagsInputString); 
        // оставляем только алфавит, цифры и пробелы
        $normalizedStr      = preg_replace('![^'.preg_quote($separator).'\pL\pN\s]+!u', '', mb_strtolower($normalizedStr));
        // убираем идущие подряд пробелы
        $normalizedStr      = preg_replace('![\s]+!u', ' ', $normalizedStr);
        $arTags             = explode(',', $normalizedStr);
        // убираем из списка тегов все пустые элементы, целиком состоящие из пробелов
        $filteredTags       = array_filter($arTags, 'trim');
        // у оставшихся тегов убираем по пробелы со сторон
        $filteredTags       = array_map('trim',$filteredTags);
        $filteredTags       = array_unique($filteredTags);
        return $filteredTags;
    }
}