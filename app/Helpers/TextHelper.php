<?php

namespace App\Helpers;

class TextHelper
{
    public static function highlight($text, $search)
    {
        if (empty($search) || empty($text)) {
            return htmlspecialchars($text);
        }

        // First escape HTML entities in the text
        $text = htmlspecialchars($text);
        
        // Escape the search term for regex
        $search = preg_quote($search, '/');
        
        // Highlight the matched text
        return preg_replace(
            "/($search)/i",
            '<mark class="bg-yellow-200 rounded px-1">$1</mark>',
            $text
        );
    }
} 