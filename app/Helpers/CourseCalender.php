<?php

namespace App\Helpers;

class CourseCalender
{
    const COURSE_START = [
        '08:00:00',
        '09:55:00',
        '13:30:00',
        '15:20:00',
        '17:10:00',
        '19:30:00',
    ];

    private $courses;
    private $currentWeek;
    private $semester;
    private $semesterStart;

    public function __construct($courses, $week)
    {
        $this->courses = $courses;
        $this->currentWeek = $week;
        $this->semester = vars('semester');
        $this->semesterStart = \Carbon\Carbon::createFromFormat('Y-m-d', vars('semester_start'))->setTimezone('Asia/Shanghai');
    }

    private function calcTime($time)
    {
        $time--;

        return [
            'week' => intval($time / 42),
            'day' => intval(($time % 42) / 6),
            'seq' => $time % 6,
        ];
    }

    public function generate()
    {
        $week = $this->currentWeek - 1;
        $monday = $this->semesterStart->clone()->add($week, 'weeks');
        $timePeriod = [$week * 42 + 1, ($week + 1) * 42];
        $vCalendar = new \Eluceo\iCal\Component\Calendar('USTB CourseTable For Week ' . $this->currentWeek);

        foreach ($this->courses as $id => $info) {
            foreach ($info['location'] as $time => $location) {
                if ($time >= $timePeriod['0'] && $time <= $timePeriod['1']) {
                    $time = $this->calcTime($time);

                    $start = $monday->clone()
                        ->add($time['day'], 'days')
                        ->setTimeFromTimeString(self::COURSE_START[$time['seq']]);
                    $end = $start->clone()
                        ->add(95, 'minutes');

                    $vEvent = new \Eluceo\iCal\Component\Event();
                    $vEvent->setDtStart($start->toDateTime())
                        ->setDtEnd($end->toDateTime())
                        ->setLocation($location)
                        ->setNoTime(false)
                        ->setSummary($info['name'])
                        ->setUseTimezone(true)
                        ->setTimezoneString('Asia/Shanghai');
                    $vCalendar->addComponent($vEvent);
                }
            }
        }

        return $vCalendar->render();
    }
}
