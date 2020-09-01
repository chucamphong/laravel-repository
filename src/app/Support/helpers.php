<?php

if (!function_exists('array_wrap')) {
    function array_wrap($value)
    {
        if (is_null($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }
}
