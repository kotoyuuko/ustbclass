<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;
use App\Helpers\CourseCalender;
use App\Models\User;
use App\Models\Course;

class SendWeeklyCourseMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $users = User::where([
            ['email_verified_at', '<>', null],
            ['elearning_id', '<>', null],
            ['elearning_pwd', '<>', null],
            ['last_sent_at', '<', Carbon::now()->startOfWeek()]
        ])->orWhere([
            ['email_verified_at', '<>', null],
            ['elearning_id', '<>', null],
            ['elearning_pwd', '<>', null],
            ['last_sent_at', '=', null]
        ])->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->last_sent_at = Carbon::now();

                try {
                    $course = Course::find($user->id);

                    if (!$course) {
                        \Log::info('User: ' . $user->id . ' course table is not ready.');
                        continue;
                    }

                    \Mail::to($user)->queue(new \App\Mail\WeeklyCalender($user));
                    \Log::info('User: ' . $user->id . ' weekly course table sent.');

                    $user->save();

                    break;
                } catch (Exception $e) {
                    \Log::info('User: ' . $user->id . ' weekly course table send failed.');
                    continue;
                }
            }
        }
    }
}
