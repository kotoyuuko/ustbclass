<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Course;

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
        $course = Course::where('user_id', $this->user->id)->first();

        return $this->view('emails.weekly_calender')
            ->with([
                'name' => $this->user->name,
                'gentime' => $course->updated_at->toDateTimeString(),
            ])
            ->attachData($course->generateCalender(), 'ustb-weekly-course-table.ics', [
                'mime' => 'text/calendar',
            ]);
    }
}
