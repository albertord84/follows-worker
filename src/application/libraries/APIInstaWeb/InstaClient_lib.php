<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\Proxy;
use ApiInstaWeb\InstaURLs;
use ApiInstaWeb\InstaClient;
use ApiInstaWeb\VerificationChoice;

/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class InstaClient_lib {

  public function __construct ()
  {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-insta_url-resource');
    require_once config_item('thirdparty-insta_client-resource');
    require_once config_item('thirdparty-verification_choice-resource');
   
    $this->InstaClient = new InstaClient("", new stdClass(), new Proxy("", "", "", ""));
   
    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  }

  public function make_insta_friendships_command(string $resource_id, string $command = 'follow', string $objetive_url = 'web/friendships') {

  }

  private function make_curl_friendships_command_str(string $url) {
    
  }

  private function make_curl_chaining_str(string $insta_id, int $N, string $cursor = NULL) {

  }

  private static function obtine_cookie_value($cookies, string $name) {

  }

  private function get_cookies_value(string $key) {

  }

  private function make_post() {

  }

  private function get_insta_csrftoken($ch) {

  }

  public static function verify_cookies(\stdClass $cookies) {

  }

  public function make_login(string $login, string $pass) {

  }

  public function like_fist_post(string $fromClient_ista_id) {

  }

  public function curlResponseHeaderCallback($ch, string $headerLine) {

  }

  public function checkpoint_requested(string $login, string $pass, VerificationChoice $choise = VerificationChoice::Email) {

  }

  public function get_challenge_data(string $challenge, string $login, VerificationChoice $choice = VerificationChoice::Email) {

  }

  public function make_checkpoint(string $login, string $code) {
    
  }

  public function TurnOn_Logs() {

  }

  public function TurnOff_Logs() {

  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
