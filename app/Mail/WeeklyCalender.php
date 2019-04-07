<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Course;
use App\Models\Vars;

class WeeklyCalender extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $week = Vars::get('current_week') + 1;
        $course = Course::where('user_id', $this->user->id)->first();
        $courseCal = new \App\Helpers\CourseCalender(json_decode($course->table, true), $week);

        return $this->subject('第 ' . $week . ' 周课程表')
            ->markdown('emails.weekly_calender')
            ->with([
                'name' => $this->user->name,
                'gentime' => \Carbon\Carbon::now()->toDateTimeString(),
            ])
            ->attachData($courseCal->generate(), 'ustb-weekly-course-table-' . $week . '.ics', [
                'mime' => 'text/calendar',
            ]);
    }
}
