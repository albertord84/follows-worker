<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require __DIR__ . '/../../vendor/autoload.php';

use \GuzzleHttp\Client;
use \GuzzleHttp\Cookie\SetCookie;
use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Psr7\Request;
use \GuzzleHttp\Psr7\Response;

class Firefox {

    protected $CI;
    protected $client;
    protected $cookies;
    protected $ua;

    public function __construct() {
        $this->CI =& get_instance();

        $this->cookies = new CookieJar;

        $this->client = new Client([
            'cookies' => $this->cookies,
            'debug' => false
        ]);

        $this->ua = $this->user_agent();

    }

    protected function rnd() {
        return sprintf(
            "%s-%s-%s",
            uniqid(),
            uniqid(),
            uniqid()
        );
    }

    protected function user_agent() {
        $last_until_now = 62;
        $v = mt_rand(58, $last_until_now);
        return sprintf(
            "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:%s.0) Gecko/20100101 Firefox/%s.0",
            $v, $v
        );
    }

    protected function is_cli() {
        return PHP_SAPI === 'cli' or defined('STDIN');
    }

    protected function get_cookie(string $cookie_name) {
        $reducer = function ($cookie, $current_cookie) use ($cookie_name) {
            $cookie_object = (object)$current_cookie;
            return $cookie_object->Name === $cookie_name ? $cookie_object->Value : $cookie;
        };
        return array_reduce($this->cookies->toArray(), $reducer, null);
    }

    protected function instagram_com() {
        $response = $this->client->send(
            new Request('GET', 'https://www.instagram.com', [
                "User-Agent" => $GLOBALS['ua'],
                "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                "DNT" => 1,
                "Connection" => 'keep-alive',
                "Upgrade-Insecure-Requests" => 1,
            ])
        );
        return $response->getBody()->getContents();
    }

    protected function batch_fetch_web() {
        $cookies = $this->client->getConfig('cookies')->toArray();
        $csrftoken = $this->get_cookie('csrftoken', $cookies);
        $mid = $this->get_cookie('mid', $cookies);
        $rur = $this->get_cookie('rur', $cookies);
        $mcd = $this->get_cookie('mcd', $cookies);
        $response = $this->client->send(
            new Request('POST', 'https://www.instagram.com/qp/batch_fetch_web/',
                [
                    "User-Agent" => $GLOBALS['ua'],
                    "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                    "Referer" => 'https://www.instagram.com/',
                    "X-CSRFToken" => $csrftoken,
                    "X-Instagram-AJAX" => 'd4e4c9fdb67b',
                    "Content-Type" => 'application/x-www-form-urlencoded',
                    "X-Requested-With" => 'XMLHttpRequest',
                    "Cookie" => "mid=$mid; rur=$rur; mcd=$mcd; csrftoken=$csrftoken",
                    "DNT" => '1',
                    "TE" => 'Trailers',
                ],
                'surfaces_to_queries=%7B%225095%22%3A%22viewer()+%7B%5Cn++eligible_promotions.surface_nux_id(%3Csurface%3E).external_gating_permitted_qps(%3Cexternal_gating_permitted_qps%3E)+%7B%5Cn++++edges+%7B%5Cn++++++priority%2C%5Cn++++++time_range+%7B%5Cn++++++++start%2C%5Cn++++++++end%5Cn++++++%7D%2C%5Cn++++++node+%7B%5Cn++++++++id%2C%5Cn++++++++promotion_id%2C%5Cn++++++++max_impressions%2C%5Cn++++++++triggers%2C%5Cn++++++++template+%7B%5Cn++++++++++name%2C%5Cn++++++++++parameters+%7B%5Cn++++++++++++name%2C%5Cn++++++++++++string_value%5Cn++++++++++%7D%5Cn++++++++%7D%2C%5Cn++++++++creatives+%7B%5Cn++++++++++title+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++content+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++footer+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++social_context+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++primary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++secondary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++dismiss_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++image+%7B%5Cn++++++++++++uri%5Cn++++++++++%7D%5Cn++++++++%7D%5Cn++++++%7D%5Cn++++%7D%5Cn++%7D%5Cn%7D%22%2C%225780%22%3A%22viewer()+%7B%5Cn++eligible_promotions.surface_nux_id(%3Csurface%3E).external_gating_permitted_qps(%3Cexternal_gating_permitted_qps%3E)+%7B%5Cn++++edges+%7B%5Cn++++++priority%2C%5Cn++++++time_range+%7B%5Cn++++++++start%2C%5Cn++++++++end%5Cn++++++%7D%2C%5Cn++++++node+%7B%5Cn++++++++id%2C%5Cn++++++++promotion_id%2C%5Cn++++++++max_impressions%2C%5Cn++++++++triggers%2C%5Cn++++++++template+%7B%5Cn++++++++++name%2C%5Cn++++++++++parameters+%7B%5Cn++++++++++++name%2C%5Cn++++++++++++string_value%5Cn++++++++++%7D%5Cn++++++++%7D%2C%5Cn++++++++creatives+%7B%5Cn++++++++++title+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++content+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++footer+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++social_context+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++primary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++secondary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++dismiss_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++image+%7B%5Cn++++++++++++uri%5Cn++++++++++%7D%5Cn++++++++%7D%5Cn++++++%7D%5Cn++++%7D%5Cn++%7D%5Cn%7D%22%7D&vc_policy=default&version=1'
            )
        );
        return $response->getBody()->getContents();
    }

    protected function ajax_bz() {
        $ua = $this->ua;
        $cookies = $this->client->getConfig('cookies')->toArray();
        $csrftoken = $this->get_cookie('csrftoken', $cookies);
        $mid = $this->get_cookie('mid', $cookies);
        $rur = $this->get_cookie('rur', $cookies);
        $mcd = $this->get_cookie('mcd', $cookies);
        $response = $this->client->send(
            new Request(
                'POST',
                'https://www.instagram.com/ajax/bz',
                [
                    "User-Agent" => $GLOBALS['ua'],
                    "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                    "Referer" => 'https://www.instagram.com/',
                    "X-CSRFToken" => $csrftoken,
                    "X-Instagram-AJAX" => 'd4e4c9fdb67b',
                    "Content-Type" => 'application/x-www-form-urlencoded',
                    "X-Requested-With" => 'XMLHttpRequest',
                    "Cookie" => "mid=$mid; rur=$rur; mcd=$mcd; csrftoken=$csrftoken",
                    "DNT" => '1',
                    "TE" => 'Trailers',
                ],
                'q=%5B%7B%22page_id%22%3A%22b8nr8x%22%2C%22posts%22%3A%5B%5B%22qe%3Aexpose%22%2C%7B%22qe%22%3A%22su_universe%22%2C%22mid%22%3A%22%s%22%7D%2C1540315780568%2C0%5D%5D%2C%22trigger%22%3A%22qe%3Aexpose%22%2C%22send_method%22%3A%22ajax%22%7D%5D&ts=1540315788333'
            )
        );
        return $response->getBody()->getContents();
    }

    protected function accounts_login() {
        $ua = $this->ua;
        $cookies = $this->client->getConfig('cookies')->toArray();
        $csrftoken = $this->get_cookie('csrftoken', $cookies);
        $mid = $this->get_cookie('mid', $cookies);
        $rur = $this->get_cookie('rur', $cookies);
        $mcd = $this->get_cookie('mcd', $cookies);
        $response = $this->client->send(
            new Request(
                'GET',
                'https://www.instagram.com/accounts/login/?__a=1',
                [
                    "User-Agent" => $GLOBALS['ua'],
                    "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                    "Referer" => 'https://www.instagram.com/',
                    "X-Instagram-GIS" => '5fe7d1651104a6619e4db3b4be631fd9',
                    "X-Requested-With" => 'XMLHttpRequest',
                    "Cookie" => "mid=$mid; rur=$rur; mcd=$mcd; csrftoken=$csrftoken",
                    "DNT" => '1',
                    "TE" => 'Trailers',
                ]
            )
        );
        return $response->getBody()->getContents();
    }

    protected function accounts_login_ajax(string $user, string $pass) {
        $ua = $this->ua;
        $cookies = $this->client->getConfig('cookies')->toArray();
        $csrftoken = $this->get_cookie('csrftoken', $cookies);
        $mid = $this->get_cookie('mid', $cookies);
        $rur = $this->get_cookie('rur', $cookies);
        $mcd = $this->get_cookie('mcd', $cookies);
        $response = $this->client->send(
            new Request(
                'POST',
                'https://www.instagram.com/accounts/login/ajax/',
                [
                    "User-Agent" => $ua,
                    "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                    "Referer" => 'https://www.instagram.com/',
                    "X-CSRFToken" => $csrftoken,
                    "X-Instagram-AJAX" => 'd4e4c9fdb67b',
                    "X-Requested-With" => 'XMLHttpRequest',
                    "Cookie" => "mid=$mid; rur=$rur; mcd=$mcd; csrftoken=$csrftoken",
                    "Content-Type" => "application/x-www-form-urlencoded",
                    "DNT" => '1',
                    "TE" => 'Trailers',
                ],
                "username=$user&password=$pass&queryParams=%7B%22source%22%3A%22auth_switcher%22%7D"
            )
        );
        $ret = $response->getBody()->getContents();
        return $ret;
    }

    public function get_cookies() {
        return $this->client->getConfig('cookies')->toArray();
    }

    public function login(string $user, string $pass) {
        try {
            $this->instagram_com();
            sleep(mt_rand(1,2));
            $this->batch_fetch_web();
            sleep(mt_rand(1,2));
            $this->ajax_bz();
            sleep(mt_rand(1,2));
            $this->accounts_login();
            sleep(mt_rand(1,2));
            $ret = $this->accounts_login_ajax($user, $pass);
            return $ret;
        } catch(\Exception $e) {
            $msg = $e->getMessage();
            $challengeData = preg_match('/checkpoint_url": "(.*)", "lock"/', $matches);
            return [
                'authenticated' => false,
                'checkpoint_url' => $challengeData[1]
            ];
        }
    }

}
