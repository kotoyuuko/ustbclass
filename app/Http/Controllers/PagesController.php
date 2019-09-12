<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;

class PagesController extends Controller
{
    public function root()
    {
        return view('pages.root');
    }

    public function courses($week = null)
    {
        $user = \Auth::user();

        if (!$user->elearning_id || !$user->elearning_pwd) {
            return redirect()
                ->route('users.edit', $user->id)
                ->with('warning', '请先设置学号和教务系统密码！');
        }

        if (!$week) {
            $week = vars('current_week');
        }

        if ($week < 1 || $week > 16) {
            return redirect()
                ->route('root')
                ->with('danger', '非法请求！');
        }

        $courses = Course::find($user->id);

        if (!$courses) {
            return redirect()
                ->route('root')
                ->with('info', '您的课程表更新任务正在队列中等待执行，请稍后查看。');
        }

        $table = courseTable(json_decode($courses->table, true), $week);

        return view('pages.courses', [
            'table' => $table,
            'week' => $week,
            'last_updated_at' => $courses->updated_at,
        ]);
    }

    public function sendCourses($week = null)
    {
        $user = \Auth::user();

        if (!$user->elearning_id || !$user->elearning_pwd) {
            return redirect()
                ->route('users.edit', $user->id)
                ->with('warning', '请先设置学号和教务系统密码！');
        }

        if (!$week) {
            return redirect()
                ->route('root')
                ->with('danger', '非法请求！');
        }

        if ($week < 1 || $week > 16) {
            return redirect()
                ->route('root')
                ->with('danger', '非法请求！');
        }

        $courses = Course::find($user->id);

        if (!$courses) {
            return redirect()
                ->route('root')
                ->with('info', '您的课程表更新任务正在队列中等待执行，请稍后查看。');
        }

        \Mail::to($user)->queue(new \App\Mail\WeeklyCalender($user, $week));
        \Log::info('User: ' . $user->id . ' week ' . $week . ' course table sent.');

        return redirect()
            ->route('courses', $week)
            ->with('info', '第 ' . $week . ' 周课程表日历已发送！');
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
