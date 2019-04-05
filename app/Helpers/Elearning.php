<?php

/**
 * Usage
 *
 * $elearning = new \App\Helpers\Elearning('学号', '密码', 周数);
 * $res = $elearning->login();
 * if (!$res) {
 *     return 'F';
 * }
 * $courseList = $elearning->courseList();
 * $parsed = $elearning->getParsedCourses($courseList);
 *
 * $ics = $elearning->generateWeekCalendar($parsed);
 */

namespace App\Helpers;

class Elearning
{
    const VPN_URL = 'http://n.ustb.edu.cn/';
    const ELEARNING_VPN = 'http/elearning.ustb.edu.cn/choose_courses/';
    const ELEARNING_URL = 'http://elearning.ustb.edu.cn/choose_courses/';

    const COURSE_START = [
        '08:00:00',
        '09:55:00',
        '13:30:00',
        '15:20:00',
        '17:10:00',
        '19:30:00',
    ];

    private $usr;
    private $pwd;

    private $semester;
    private $semesterStart;
    private $currentWeek;

    protected $useVPN;
    protected $client;
    protected $cookies;

    public function __construct($usr, $pwd, $week)
    {
        $this->usr = $usr . ',undergraduate';
        $this->pwd = $pwd;
        $this->useVPN = config('ustb.vpn');

        $this->semester = vars('semester');
        $this->semesterStart = \Carbon\Carbon::createFromFormat('Y-m-d', vars('semester_start'))->setTimezone('Asia/Shanghai');
        $this->currentWeek = $week;

        $this->guzzleInit();
    }

    private function guzzleInit()
    {
        $this->cookies = new \GuzzleHttp\Cookie\CookieJar;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->useVPN ? self::VPN_URL : self::ELEARNING_URL,
            'timeout' => 5.0,
        ]);
    }

    private function url($url, $type = 'elearning')
    {
        if ($type == 'elearning' && $this->useVPN) {
            $url = self::ELEARNING_VPN . $url;
        }

        return $url;
    }

    private function parseCourse($course)
    {
        $info = [
            'name' => $course['KCM'],
            'location' => []
        ];

        foreach ($course['SKSJDD'] as $time => $location) {
            $info['location'][$time] = $location['0'];
        }

        return [
            'id' => $course['KCH'],
            'info' => $info,
        ];
    }

    public function vpnLogin()
    {
        $response = $this->client->request('POST', $this->url('do-login', 'vpn'), [
            'cookies' => $this->cookies,
            'query' => [
                'auth_type' => 'local',
                'username' => config('ustb.usr'),
                'password' => config('ustb.pwd'),
                'sms_code' => '',
            ],
        ]);
        $code = $response->getStatusCode();

        return $code == '302';
    }

    public function login()
    {
        if ($this->useVPN && !$this->vpnLogin()) {
            return false;
        }

        $response = $this->client->request('POST', $this->url('j_spring_security_check'), [
            'cookies' => $this->cookies,
            'query' => [
                'j_username' => $this->usr,
                'j_password' => $this->pwd,
            ],
        ]);
        $body = $response->getBody();

        return strpos($body, 'success:true') !== false;
    }

    public function courseList()
    {
        $response = $this->client->request('POST', $this->url('choosecourse/commonChooseCourse_courseList_loadTermCourses.action'), [
            'cookies' => $this->cookies,
            'query' => [
                'listXnxq' => $this->semester,
                'uid' => $this->usr,
            ],
        ]);
        $body = json_decode($response->getBody(), true);
        return $body['selectedCourses'];
    }

    public function getParsedCourses($courseList)
    {
        $courses = [];

        foreach ($courseList as $course) {
            $parsedCourse = $this->parseCourse($course);
            $courses[$parsedCourse['id']] = $parsedCourse['info'];

            if (count($course['PTK']) > 0) {
                foreach ($course['PTK'] as $ptk) {
                    $parsedCourse = $this->parseCourse($ptk);
                    $courses['P' . $parsedCourse['id']] = $parsedCourse['info'];
                }
            }
        }

        return $courses;
    }

    public function generateWeekCalendar($courses)
    {
        $week = $this->currentWeek - 1;
        $monday = $this->semesterStart->clone()->add($week, 'weeks');
        $timePeriod = [$week * 42 + 1, ($week + 1) * 42];
        $vCalendar = new \Eluceo\iCal\Component\Calendar('USTB CourseTable For Week ' . $this->currentWeek);

        foreach ($courses as $id => $info) {
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

        // return response($vCalendar->render())
        //     ->header('Content-Type', 'text/calendar; charset=utf-8')
        //     ->header('Content-Disposition', 'attachment; filename="ustb-coursetable-week-' . $this->currentWeek . '.ics"');

        return $vCalendar->render();
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

    public function __destruct()
    {
        if ($this->useVPN) {
            $this->client->request('GET', $this->url('logout', 'vpn'), [
                'cookies' => $this->cookies,
            ]);
        }
    }
}
