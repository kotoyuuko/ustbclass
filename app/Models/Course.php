<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id', 'table',
    ];
}
