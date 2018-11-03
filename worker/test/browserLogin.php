<?php
/**
 * Este script solo depende de:
 *
 * - guzzlehttp/guzzle
 * - symfony/http-foundation
 *
 * Estas bibliotecas deben existir dentro de algun directorio "vendor",
 * y deben estar debidamente registradas en el "autoload.php". Luego,
 * basta con hacer un require de ese autoload.php, este donde este...
 * Esto quiere decir, que la primera linea del require, es lo unico que
 * habria que modificar si nos llevamos este script integramente para
 * cualquier servidor.
 *
 * Tambien hay que modificar los valores de la base de datos y las
 * credenciales de acceso a la misma, dentro de la clase DB.
 *
 * En cuanto a los parametros, se deben pasar por POST, y codificados
 * como JSON, en el cuerpo, o sea, la seccion data de la peticion. Seria
 * asi:
 *
 * {"user":"napoleon","pass":"KonquerTheWor1d","proxy":3}
 *
 * Escogi hacerlo de esta forma porque la mayoria de las bibliotecas de
 * JavaScript que usan AJAX, estan migrando el formato de la peticion
 * a objetos JSON, en lugar de una cadena "url-form-encoded" como se hacia
 * antiguamente.
 *
 * Los parametros que se deben pasar son estos:
 *
 * - user: el nombre del usuario de Instagram
 * - pass: la contraseña del usuario
 * - proxy: numero del proxy que se usara o que se usa normalmente para
 * autenticar al cliente.
 *
 * El resultado que se obtiene es un JSON con las cookies de la sesion
 * abierta. Si algo sale mal, se devuelve un JSON en el que "authenticated"
 * es "false".
 *
 */

require __DIR__ . '/../../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use \GuzzleHttp\Client;
use \GuzzleHttp\Cookie\CookieJar;
use \GuzzleHttp\Psr7\Request;

class Firefox {

    protected $client;
    protected $cookies;
    protected $ua;
    protected $log_file;

    /**
     * @param $proxy Debe ser una cadena con la "ip:port". Tambien puede
     * tener el formato "user:passwd@ip:port".
     */
    public function __construct($proxy = null) {
        $this->cookies = new CookieJar;

        $default_options = [
            'cookies' => $this->cookies,
            'debug' => false,
        ];

        $options = array_merge(
            $default_options,
            $proxy === null ? [] : [ 'proxy' => "tcp://$proxy" ]
        );

        $this->client = new Client($options);

        $this->ua = $this->user_agent();
    }

    public function set_log($logfile) {
        $this->log_file = $logfile;
    }

    protected function log_event($data) {
        log_event($this->log_file, $data);
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
                "User-Agent" => $this->ua,
                "Accept" => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                "Accept-Language" => 'es,en-US;q=0.7,en;q=0.3',
                "DNT" => 1,
                "Connection" => 'keep-alive',
                "Upgrade-Insecure-Requests" => 1,
            ])
        );
        $ret = $response->getBody()->getContents();
        $this->log_event($ret);
        return $ret;
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
                    "User-Agent" => $this->ua,
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
        $ret = $response->getBody()->getContents();
        $this->log_event($ret);
        return $ret;
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
                    "User-Agent" => $this->ua,
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
        $ret = $response->getBody()->getContents();
        $this->log_event($ret);
        return $ret;
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
                    "User-Agent" => $this->ua,
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
        $ret = $response->getBody()->getContents();
        $this->log_event($ret);
        return $ret;
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
        $this->log_event($ret);
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
            preg_match(
                '/checkpoint_url": "(.*)", "lock"/',
                $msg,
                $matches
            );
            return json_encode([
                'authenticated' => false,
                'checkpoint_url' => 'https://www.instagram.com' . $matches[1]
            ]);
        }
    }

}

function prepare_log() {
    $d = date("Ymd");
    $log_name = "/tmp/browser-login-$d.log";
    fopen($log_name, "w");
    return $log_name;
}

function log_event($log_file, $data) {
    $record = sprintf(
        "%s - %s",
        date("H:i:s"),
        $data
    );
    file_put_contents($log_file, $record . PHP_EOL, FILE_APPEND);
}

function get_proxy($proxyId) {
    $ini_file = trim(file_get_contents(__DIR__ . '/.dbIni'));
    $config = parse_ini_file($ini_file);
    $conn = mysqli_connect($config['host'],
        $config['user'], $config['pass'], $config['db'],
        3306, '/opt/lampp/var/mysql/mysql.sock');
    $result = mysqli_query($conn, "SELECT * FROM Proxy WHERE idProxy=$proxyId");
    $proxy = mysqli_fetch_object($result);
    $json = json_encode($proxy);
    return $json;
}

/////////////////////////////////////////////////////////////////////

$log_file = prepare_log();

$request = SymfonyRequest::createFromGlobals();
$content = $request->getContent();
log_event($log_file, "Params: " . $content);

$params = json_decode($content, true);

$proxy = null;

if ($params['proxy']) {
    $proxy_data = get_proxy($params['proxy']);
    $data = json_decode($proxy_data, false);
    log_event($log_file, "Proxy: " . $proxy_data);
    $proxy_str = sprintf(
        "%s:%s@%s:%s",
        $data->proxy_user,
        $data->proxy_password,
        $data->proxy,
        $data->port
    );
    log_event($log_file, "Proxy string: " . $proxy_str);
}

$firefox = new Firefox($proxy_str);
$firefox->set_log($log_file);

$resp = json_decode(
    $firefox->login($params['user'], $params['pass']),
    false
);

if ($resp->authenticated) {
    echo json_encode($firefox->get_cookies());
    exit();
}

echo json_encode($resp);
