<?php
if (!function_exists('get_excerpt')) {
    function get_excerpt($content, $length = 200) {
        $plain = strip_tags($content);
        if (mb_strlen($plain) <= $length) {
            return $plain;
        }
        return mb_substr($plain, 0, $length) . '...';
    }
}
