<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Elearning;

class Course extends Model
{
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id', 'table',
    ];
}
