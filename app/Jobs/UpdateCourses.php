<?php

namespace App\Jobs;

use App\Helpers\Elearning;
use App\Models\Course;
use App\Models\User;
use App\Models\Vars;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class UpdateCourses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $users = User::where([
            ['email_verified_at', '<>', null],
            ['elearning_id', '<>', null],
            ['elearning_pwd', '<>', null],
            ['coursed_at', '<', Carbon::now()->startOfWeek()],
        ])->orWhere([
            ['email_verified_at', '<>', null],
            ['elearning_id', '<>', null],
            ['elearning_pwd', '<>', null],
            ['coursed_at', '=', null],
        ])->get();

        if (count($users) > 0) {
            foreach ($users as $user) {
                $user->coursed_at = Carbon::now();
                if ($user->token == null) {
                    $user->token = Str::random(16);
                }

                try {
                    $elearning = new Elearning($user->elearning_id, $user->elearning_pwd, Vars::get('current_week') + 1);
                    $logged = $elearning->login();
                    if (!$logged) {
                        \Log::info('User: ' . $user->id . ' elearning login failed.');
                        $user->save();
                        continue;
                    }

                    $courseList = $elearning->courseList();
                    $parsed = $elearning->getParsedCourses($courseList);

                    $course = Course::findOrNew($user->id);
                    $course->user_id = $user->id;
                    $course->table = json_encode($parsed);
                    $course->save();
                    $user->save();

                    \Log::info('User: ' . $user->id . ' course table updated.');

                    break;
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }
}
