<?php

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function vars($key, $value = false)
{
    if ($value) {
        \App\Models\Vars::set($key, $value);
    } else {
        $cached = cache('vars_' . $key);
        if ($cached) {
            $value = $cached;
        } else {
            $value = \App\Models\Vars::get($key);
        }
    }
    cache(['vars_' . $key => $value], now()->addDays(1));

    return $value;
}
