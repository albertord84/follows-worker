<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';

/**
 * Description of InstaApi
 *
 * @author dumbu
 */
class InstaApiLib {

    public function login(string $username, string $password, \business\cls\Proxy $proxy) {
        $ApiInstaWeb = new ApiInstaWeb\InstaApi();
        $ApiInstaWeb->login($username, $password, $proxy);
    }

    public static function make_query(string $query, string $variables, \stdClass $cookies, \business\cls\Proxy $proxy = NULL) {
        $ApiInstaWeb = new ApiInstaWeb\InstaApi();
        $ApiInstaWeb->make_query($query, $variables, $cookies, $proxy);
    }

}
