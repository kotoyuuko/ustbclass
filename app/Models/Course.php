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

    public function generateCalender()
    {
        $elearning = new Elearning('', '', Vars::get('current_week'));
        return generateWeekCalendar($this->table);
    }
}
