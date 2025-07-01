<?php
// app/Traits/HasAutoExcerpt.php

namespace App\Traits;

trait HasAutoExcerpt
{
    public static function bootHasAutoExcerpt()
    {
        static::saving(function ($model) {
            if (empty($model->excerpt) && !empty($model->content)) {
                $model->excerpt = static::generateExcerpt($model->content);
            }
        });
    }

    public static function generateExcerpt($content, $length = 200)
    {
        $plain = strip_tags($content);
        return strlen($plain) > $length ? substr($plain, 0, $length) . '...' : $plain;
    }
}
