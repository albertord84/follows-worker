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
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log_path = '/tmp';
$channel = 'follows-worker';
$date_id = date('Ymd');

// funciones puras

function logFile($log_path, $channel, $date_id) {
    return sprintf(
        "%s/%s.%s.log",
        $log_path,
        $channel,
        $date_id
    );
}

function userAgent() {
    return "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chromium/70.0.3538.77 Chrome/70.0.3538.77 Safari/537.36";
}

function httpClient($options = []) {
    $cookies = new CookieJar;

    $default_options = [
        'cookies' => $cookies,
        'debug' => false,
    ];

    $client = new Client(
        array_merge(
            $default_options,
            $options
        )
    );

    return $client;
}

function getCookie($name, $cookies) {
    $reducer = function ($cookie, $currentCookie) use ($name) {
        $cookieObject = (object)$currentCookie;
        return $cookieObject->Name === $name ? $cookieObject->Value : $cookie;
    };
    return array_reduce($cookies, $reducer, null);
}

function createLogger($log_path, $channel, $date_id) {
    $log = logFile($log_path, $channel, $date_id);
    $logger = new Logger($channel);
    $logger->pushHandler(new StreamHandler($log));
    return $logger;
}

function getRequestParams() {
    $request = SymfonyRequest::createFromGlobals();
    $content = $request->getContent();
    $params = json_decode($content, true);
    return $params;
}

function getProxyStr($proxyParam) {
    $proxyData = getProxy($proxyParam);
    $data = json_decode($proxyData, false);
    $proxyStr = sprintf(
        "%s:%s@%s:%s",
        $data->proxy_user,
        $data->proxy_password,
        $data->proxy,
        $data->port
    );
    return $proxyStr;
}

// funciones impuras

function logEvent($logger, $message, $context = [], $level = Logger::INFO) {
    $logger->addRecord($level, $message, $context);
}

function logCheckpointUrl($logger, $exceptionMsg) {
    preg_match('/checkpoint_url": "(.*)", "lock"/', $exceptionMsg, $matches);
    $prefix = 'https://www.instagram.com';
    $checkPointUrl = isset($matches[1]) ? $prefix . $matches[1] : false;
    if ($checkPointUrl) {
        logEvent($logger, "Intente desafio en:", ['url' => $checkPointUrl],
            Logger::CRITICAL);
    }
}

function requestInstagramPage($client) {
    $response = $client->send(
        new Request('GET', 'https://www.instagram.com', [
            'authority' => 'www.instagram.com',
            'upgrade-insecure-requests' => '1',
            'user-agent' => userAgent(),
            'accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'en-US,en;q=0.9',
        ])
    );
    $ret = $response->getBody()->getContents();
    return $ret;
}

function requestBatchFetchWeb($client) {
    $cookies = $client->getConfig('cookies')->toArray();
    $csrftoken = getCookie('csrftoken', $cookies);
    $mid = getCookie('mid', $cookies);
    $rur = getCookie('rur', $cookies);
    $mcd = getCookie('mcd', $cookies);
    $cookieHeader = sprintf(
        "rur=%s; mid=%s; mcd=%s; csrftoken=%s",
        $rur,
        $mid,
        $mcd,
        $csrftoken
    );
    $data = "surfaces_to_queries=%7B%225095%22%3A%22viewer()+%7B%5Cn++eligible_promotions.surface_nux_id(%3Csurface%3E).external_gating_permitted_qps(%3Cexternal_gating_permitted_qps%3E)+%7B%5Cn++++edges+%7B%5Cn++++++priority%2C%5Cn++++++time_range+%7B%5Cn++++++++start%2C%5Cn++++++++end%5Cn++++++%7D%2C%5Cn++++++node+%7B%5Cn++++++++id%2C%5Cn++++++++promotion_id%2C%5Cn++++++++max_impressions%2C%5Cn++++++++triggers%2C%5Cn++++++++template+%7B%5Cn++++++++++name%2C%5Cn++++++++++parameters+%7B%5Cn++++++++++++name%2C%5Cn++++++++++++string_value%5Cn++++++++++%7D%5Cn++++++++%7D%2C%5Cn++++++++creatives+%7B%5Cn++++++++++title+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++content+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++footer+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++social_context+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++primary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++secondary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++dismiss_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++image+%7B%5Cn++++++++++++uri%5Cn++++++++++%7D%5Cn++++++++%7D%5Cn++++++%7D%5Cn++++%7D%5Cn++%7D%5Cn%7D%22%2C%225780%22%3A%22viewer()+%7B%5Cn++eligible_promotions.surface_nux_id(%3Csurface%3E).external_gating_permitted_qps(%3Cexternal_gating_permitted_qps%3E)+%7B%5Cn++++edges+%7B%5Cn++++++priority%2C%5Cn++++++time_range+%7B%5Cn++++++++start%2C%5Cn++++++++end%5Cn++++++%7D%2C%5Cn++++++node+%7B%5Cn++++++++id%2C%5Cn++++++++promotion_id%2C%5Cn++++++++max_impressions%2C%5Cn++++++++triggers%2C%5Cn++++++++template+%7B%5Cn++++++++++name%2C%5Cn++++++++++parameters+%7B%5Cn++++++++++++name%2C%5Cn++++++++++++string_value%5Cn++++++++++%7D%5Cn++++++++%7D%2C%5Cn++++++++creatives+%7B%5Cn++++++++++title+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++content+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++footer+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++social_context+%7B%5Cn++++++++++++text%5Cn++++++++++%7D%2C%5Cn++++++++++primary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++secondary_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++dismiss_action%7B%5Cn++++++++++++title+%7B%5Cn++++++++++++++text%5Cn++++++++++++%7D%2C%5Cn++++++++++++url%2C%5Cn++++++++++++limit%2C%5Cn++++++++++++dismiss_promotion%5Cn++++++++++%7D%2C%5Cn++++++++++image+%7B%5Cn++++++++++++uri%5Cn++++++++++%7D%5Cn++++++++%7D%5Cn++++++%7D%5Cn++++%7D%5Cn++%7D%5Cn%7D%22%7D&vc_policy=default&version=1";
    $response = $client->send(
        new Request('POST', 'https://www.instagram.com/qp/batch_fetch_web/', [
            'origin' => 'https://www.instagram.com',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'en-US,en;q=0.9',
            'x-requested-with' => 'XMLHttpRequest',
            'user-agent' => userAgent(),
            'cookie' => $cookieHeader,
            'x-csrftoken' => $csrftoken,
            'x-instagram-ajax' => 'c96d7302ceff',
            'content-type' => 'application/x-www-form-urlencoded',
            'accept' => '*/*',
            'referer' => 'https://www.instagram.com/',
            'authority' => 'www.instagram.com',
        ], $data)
    );
    $ret = $response->getBody()->getContents();
    return $ret;
}

function requestAccountLoginA1($client) {
    $cookies = $client->getConfig('cookies')->toArray();
    $csrftoken = getCookie('csrftoken', $cookies);
    $mid = getCookie('mid', $cookies);
    $rur = getCookie('rur', $cookies);
    $mcd = getCookie('mcd', $cookies);
    $cookieHeader = sprintf(
        "rur=%s; mid=%s; mcd=%s; csrftoken=%s",
        $rur,
        $mid,
        $mcd,
        $csrftoken
    );
    $response = $client->send(
        new Request('GET', 'https://www.instagram.com/accounts/login/?__a=1', [
            'cookie' => $cookieHeader,
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'en-US,en;q=0.9',
            'user-agent' => userAgent(),
            'accept' => '*/*',
            'referer' => 'https://www.instagram.com/accounts/login/?source=auth_switcher',
            'authority' => 'www.instagram.com',
            'x-requested-with' => 'XMLHttpRequest',
            'x-instagram-gis' => 'aa6cf2e54d88493bca91c3297948d39f',
        ])
    );
    $ret = $response->getBody()->getContents();
    return $ret;
}

function requestLoginAjax($client, $userName, $password) {
    $cookies = $client->getConfig('cookies')->toArray();
    $csrftoken = getCookie('csrftoken', $cookies);
    $mid = getCookie('mid', $cookies);
    $rur = getCookie('rur', $cookies);
    $mcd = getCookie('mcd', $cookies);
    $cookieHeader = sprintf(
        "rur=%s; mid=%s; mcd=%s; csrftoken=%s",
        $rur,
        $mid,
        $mcd,
        $csrftoken
    );
    $data = sprintf(
        "username=%s&password=%s&queryParams=%%7B%%22source%%22%%3A%%22auth_switcher%%22%%7D",
        $userName, $password
    );
    $response = $client->send(
        new Request('POST', 'https://www.instagram.com/accounts/login/ajax/', [
            'origin' => 'https://www.instagram.com',
            'accept-encoding' => 'gzip, deflate, br',
            'accept-language' => 'en-US,en;q=0.9',
            'x-requested-with' => 'XMLHttpRequest',
            'user-agent' => userAgent(),
            'cookie' => $cookieHeader,
            'x-csrftoken' => $csrftoken,
            'x-instagram-ajax' => 'c96d7302ceff',
            'content-type' => 'application/x-www-form-urlencoded',
            'accept' => '*/*',
            'referer' => 'https://www.instagram.com/accounts/login/?source=auth_switcher',
            'authority' => 'www.instagram.com',
        ], $data)
    );
    $ret = $response->getBody()->getContents();
    return $ret;
}

function getProxy($proxyId) {
    $iniFile = trim(file_get_contents(__DIR__ . '/.dbIni'));
    $config = parse_ini_file($iniFile);
    $conn = mysqli_connect($config['host'],
        $config['user'], $config['pass'], $config['db'],
        3306, '/opt/lampp/var/mysql/mysql.sock');
    $result = mysqli_query($conn, "SELECT * FROM Proxy WHERE idProxy=$proxyId");
    $proxy = mysqli_fetch_object($result);
    $json = json_encode($proxy);
    return $json;
}

function returnedCookies($client) {
    $cookies = $client->getConfig('cookies')->toArray();
    // print_r($cookies);
    $result = array_reduce($cookies, function($newCookies, $currentCookie) {
        if ($currentCookie['Value'] !== '') {
            $name = $currentCookie['Name'];
            $newCookies[$name] = $currentCookie['Value'];
        }
        return $newCookies;
    }, []);
    return json_encode((object) $result);
}

/////////////////////////////////////////////////////////////
////////************ PUNTO DE ENTRADA *************//////////
/////////////////////////////////////////////////////////////

try {
    // creando el log y el logger
    $logger = createLogger($log_path, $channel, $date_id);
    // anunciando el comienzo...
    logEvent($logger, 'COMENZANDO INTENTO DE LOGUEO',
        ['userAgent' => userAgent()]);
    // obteniendo parametros de la peticion web
    $params = getRequestParams();
    logEvent($logger, 'Parametros obtenidos: ', $params);
    // consiguiendo el proxy basado en los parametros de la peticion web
    $proxyStr = null;
    if (isset($params['proxy'])) {
        $proxyStr = getProxyStr($params['proxy']);
        logEvent($logger, 'Cadena del Proxy de acceso: ', [$proxyStr]);
    }
    // creando el cliente http
    $options = $proxyStr !== null ? [ 'proxy' => "tcp://$proxyStr" ] : [];
    $client = httpClient($options);
    logEvent($logger, 'Creado el cliente HTTP');
    // secuencia de peticiones a Instagram
    requestInstagramPage($client);
    logEvent($logger, 'Conectado a pagina inicial de Instagram...');
    $batchFetchWebResponse = requestBatchFetchWeb($client);
    logEvent($logger, $batchFetchWebResponse);
    $accountLoginResponse = requestAccountLoginA1($client);
    logEvent($logger, $accountLoginResponse);
    $loginAjax = requestLoginAjax($client, $params['user'], $params['pass']);
    logEvent($logger, $loginAjax);
    $authResp = json_decode($loginAjax, false);
    if (!$authResp->authenticated) {
        throw new \Exception('No se pudo autenticar al usuario ' . $params['user']);
    }
    logEvent($logger, 'SESIÓN INICIADA', [ 'user' => $params['user'] ]);
    echo returnedCookies($client);
}
catch (\Exception $ex) {
    logEvent($logger, $ex->getMessage(), [], Logger::CRITICAL);
    logCheckpointUrl($logger, $ex->getMessage());
}
