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
            ],
        ]);
        $body = $response->getBody();

        return strpos($body, '/logout') !== false;
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
}
