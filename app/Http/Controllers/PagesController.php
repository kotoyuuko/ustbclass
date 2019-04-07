<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

class PagesController extends Controller
{
    public function root()
    {
        $user = \Auth::user();

        if (!$user->elearning_id || !$user->elearning_pwd) {
            return redirect()
                ->route('users.edit', $user->id)
                ->with('warning', '请先设置学号和教务系统密码！');
        }

        return view('pages.root');
    }

    public function help()
    {
        return view('pages.help');
    }

    public function calendar($token, User $user, $week)
    {
        if ($user->token != $token) {
            return response('Access Denied', 403);
        }

        $course = Course::where('user_id', $user->id)->first();
        $courseCal = new \App\Helpers\CourseCalender(json_decode($course->table, true), $week);

        return response($courseCal->generate())
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="ustb-weekly-course-table-' . $week . '.ics"');
    }
}
