<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function vars($key, $value = false)
{
    $cacheKey = 'vars_' . $key;

    if ($value) {
        \App\Models\Vars::set($key, $value);
    } else {
        $cached = cache($cacheKey);
        if ($cached) {
            $value = $cached;
        } else {
            $value = \App\Models\Vars::get($key);
        }
    }
    cache([$cacheKey => $value], now()->addDays(1));

    return $value;
}
