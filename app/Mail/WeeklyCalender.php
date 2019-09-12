<?php

namespace App\Mail;

use App\Models\Course;
use App\Models\Vars;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WeeklyCalender extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $week;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $week = null)
    {
        $this->user = $user;
        $this->week = $week !== null ? $week : Vars::get('current_week') + 1;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $week = $this->week;
        $course = Course::where('user_id', $this->user->id)->first();
        $courseCal = new \App\Helpers\CourseCalender(json_decode($course->table, true), $week);

        $link = null;
        if ($this->user->token != null) {
            $link = route('calendar', [
                'token' => $this->user->token,
                'user' => $this->user,
                'week' => $week,
            ]);
        }

        return $this->subject('第 ' . $week . ' 周课程表')
            ->markdown('emails.weekly_calender')
            ->with([
                'name' => $this->user->name,
                'gentime' => \Carbon\Carbon::now()->toDateTimeString(),
                'link' => $link,
            ])
            ->attachData($courseCal->generate(), 'ustb-weekly-course-table-' . $week . '.ics', [
                'mime' => 'text/calendar',
            ]);
    }
}
