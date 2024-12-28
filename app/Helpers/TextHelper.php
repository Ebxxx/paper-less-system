<?php

namespace App\Helpers;

class TextHelper
{
    public static function highlight($text, $search)
    {
        if (empty($search) || empty($text)) {
            return $text;
        }

        $search = preg_quote($search, '/');
        return preg_replace(
            "/($search)/i",
            '<mark class="bg-yellow-200 rounded px-1">$1</mark>',
            htmlspecialchars($text)
        );
    }
} 