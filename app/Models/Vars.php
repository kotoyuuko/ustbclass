<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vars extends Model
{
    protected $primaryKey = 'key';
    protected $fillable = [
        'key', 'value',
    ];

    public static function get($key)
    {
        $obj = self::where('key', $key)->firstOrFail();

        return $obj->value;
    }

    public static function set($key, $value)
    {
        $obj = self::where('key', $key)->firstOrFail();
        $obj->value = $value;
        $obj->save();
    }
}
