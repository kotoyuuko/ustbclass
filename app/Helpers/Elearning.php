<?php

namespace App\Helpers;

define('VPN_URL', 'https://n.ustb.edu.cn/');
define('ELEARNING_DOMAIN', 'elearning.ustb.edu.cn');

class Elearning
{
    private $usr;
    private $pwd;

    private $semester;
    private $semesterStart;
    private $currentWeek;

    protected $useVPN;
    protected $client;
    protected $cookies;
    protected $accessPoint;

    public function __construct($usr, $pwd, $week)
    {
        $this->usr = $usr . ',undergraduate';
        $this->pwd = $pwd;
        $this->useVPN = config('ustb.vpn');

        $this->semester = vars('semester');
        $this->semesterStart = \Carbon\Carbon::createFromFormat('Y-m-d', vars('semester_start'))->setTimezone('Asia/Shanghai');
        $this->currentWeek = $week;

        $this->accessPoint = 'http://' . ELEARNING_DOMAIN . '/choose_courses/';
        if ($this->useVPN) {
            $vpn = new FxxkWengineVPN();
            $this->accessPoint = VPN_URL . 'http/' . $vpn->encryptUrl(ELEARNING_DOMAIN) . '/choose_courses/';
        }

        $this->guzzleInit();
    }

    private function guzzleInit()
    {
        $this->cookies = new \GuzzleHttp\Cookie\CookieJar;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->accessPoint,
            'timeout' => 30.0,
            'verify' => \Composer\CaBundle\CaBundle::getSystemCaRootBundlePath(),
        ]);
    }

    private function parseCourse($course)
    {
        $info = [
            'name' => $course['KCM'],
            'location' => [],
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
        $this->client->request('GET', VPN_URL, [
            'cookies' => $this->cookies,
        ]);

        $this->cookies->setCookie(new \GuzzleHttp\Cookie\SetCookie([
            'Domain' => 'n.ustb.edu.cn',
            'Name' => 'remember_token',
            'Value' => config('ustb.token'),
            'Discard' => false,
        ]));

        $response = $this->client->request('GET', VPN_URL, [
            'cookies' => $this->cookies,
        ]);
        $body = $response->getBody();
        $success = strpos($body, '/logout') !== false;

        return $success;
    }

    public function login()
    {
        if ($this->useVPN && !$this->vpnLogin()) {
            \Log::info('vpn login failed.');
            return false;
        }

        $response = $this->client->request('POST', 'j_spring_security_check', [
            'cookies' => $this->cookies,
            'form_params' => [
                'j_username' => $this->usr,
                'j_password' => $this->pwd,
            ],
        ]);
        $body = $response->getBody();

        return strpos($body, 'success:true') !== false;
    }

    public function courseList()
    {
        $response = $this->client->request('POST', 'choosecourse/commonChooseCourse_courseList_loadTermCourses.action', [
            'cookies' => $this->cookies,
            'form_params' => [
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
