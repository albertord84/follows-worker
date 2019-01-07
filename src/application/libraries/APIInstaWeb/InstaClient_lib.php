<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\Exceptions\InstaCheckpointRequiredException;
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

  public function __construct() {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-insta_url-resource');
    require_once config_item('thirdparty-insta_client-resource');
    require_once config_item('thirdparty-verification_choice-resource');
    require_once config_item('insta_checkpoint_required-exception-class');
    
//    if (file_exists(config_item('insta_checkpoint_required-exception-class'))) echo "el fichero existe<br><br>";

    $this->CI = &get_instance();
    $this->CI->load->model("db_model");

    $this->InstaClient = new InstaClient("", new stdClass(), new Proxy("", "", "", ""));

    echo "se cargo satisfactoriamente la libreria: " . __CLASS__ . "<br><br>";
  }

  public function make_insta_friendships_command(string $resource_id, string $command = 'follow', string $objetive_url = 'web/friendships') {

    $this->InstaClient->make_insta_friendships_command($resource_id, $command, $objetive_url);
  }

  private function make_curl_friendships_command_str(string $url) {

    $this->InstaClient->make_curl_friendships_command_str($url);
  }

  private function make_curl_chaining_str(string $insta_id, int $N, string $cursor = NULL) {

    $this->InstaClient->make_curl_chaining_str($insta_id, $N, $cursor);
  }

  private static function obtine_cookie_value($cookies, string $name) {

    $this->InstaClient->obtine_cookie_value($cookies, $name);
  }

  private function get_cookies_value(string $key) {

    $this->InstaClient->get_cookies_value($key);
  }

  private function make_post() {

    $this->InstaClient->make_post();
  }

  private function get_insta_csrftoken($ch) {

    $this->InstaClient->get_insta_csrftoken($ch);
  }

  public static function verify_cookies(\stdClass $cookies) {

    $this->InstaClient->verify_cookies($cookies);
  }

  public function make_login(string $login, string $pass) {
    try {
      $result = $this->InstaClient->make_login($login, $pass);
      return $result;
    } catch (InstaCheckpointRequiredException $exc) {
      
      /** @TODO Pasar quien llame a esta libreria la responsabilidad de insertar en el whashdog **/
      //$this->CI->db_model->InsertEventToWashdog($Client->id, $exc->getMessage(), $source);

      $result->json_response->verify_link = $exc->GetChallange();
      $result->json_response->authenticated = false;
      $result->json_response->status = 'ok';
      $result->json_response->message = $exc->getMessage();
    } catch (\Exception $exc) {
      $this->db_model->InsertEventToWashdog($Client->id, $exc->getMessage(), $source);

      $result->json_response->authenticated = false;
      $result->json_response->status = 'ok';
      $result->json_response->message = $exc->getMessage();
    }
  }

  public function like_fist_post(string $fromClient_ista_id) {

    $this->InstaClient->like_fist_post($fromClient_ista_id);
  }

  public function curlResponseHeaderCallback($ch, string $headerLine) {

    $this->InstaClient->curlResponseHeaderCallback($ch, $headerLine);
  }

  public function checkpoint_requested(string $login, string $pass, VerificationChoice $choise = VerificationChoice::Email) {

    $this->InstaClient->checkpoint_requested($login, $pass, $choise);
  }

  public function get_challenge_data(string $challenge, string $login, VerificationChoice $choice = VerificationChoice::Email) {

    $this->InstaClient->get_challenge_data($challenge, $login, $choice);
  }

  public function make_checkpoint(string $login, string $code) {

    $this->InstaClient->make_checkpoint($login, $code);
  }

  public function TurnOn_Logs() {

    $this->InstaClient->TurnOn_Logs();
  }

  public function TurnOff_Logs() {

    $this->InstaClient->TurnOff_Logs();
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg() {
    echo "<h2>se invoco bien un metodo de la lib: " . __CLASS__ . "->" . __FUNCTION__ . "()</h2>";
  }

}