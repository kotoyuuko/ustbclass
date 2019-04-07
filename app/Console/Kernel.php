<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (\App::environment('local')) {
            $schedule->job(new \App\Jobs\UpdateCourses)->everyMinute();
            $schedule->job(new \App\Jobs\SendWeeklyCourseMail)->everyMinute();
        } else {
            $schedule->job(new \App\Jobs\UpdateCourses)
                ->timezone('Asia/Shanghai')
                ->everyFiveMinutes();
            $schedule->job(new \App\Jobs\SendWeeklyCourseMail)
                ->timezone('Asia/Shanghai')
                ->weekends()
                ->everyFiveMinutes();
            $schedule->job(new \App\Jobs\UpdateCurrentWeek)
                ->timezone('Asia/Shanghai')
                ->weeklyOn(1, '8:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
