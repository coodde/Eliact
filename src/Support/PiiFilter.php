<?php

namespace Coodde\Eliact\Support;

class PiiFilter
{
    public static function sanitize(string $content): string
    {
        $content = preg_replace('/[\w._%+-]+@[\w.-]+\.[a-zA-Z]{2,}/', '[email]', $content);
        $content = preg_replace('/\+?\d[\d\s\-()]{7,}/', '[phone]', $content);
        $content = preg_replace('/\b[A-Z0-9._%+-]{8,}\b/i', '[token]', $content);
        return $content;
    }
}
