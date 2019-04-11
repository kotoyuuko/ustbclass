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

function courseTable($courses, $week)
{
    $table = [];
    $range = [($week - 1) * 42 + 1, $week * 42];

    foreach ($courses as $id => $info) {
        foreach ($info['location'] as $time => $location) {
            if ($time < $range['0'] || $time > $range['1']) {
                continue;
            }

            $table[$time - $range['0'] + 1][$id] = [
                'name' => $info['name'],
                'location' => $location,
            ];
        }
    }

    return $table;
}
