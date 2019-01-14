<?php

defined('BASEPATH') OR exit('No direct script access allowed');

  
use ApiInstaWeb\Proxy;
use ApiInstaWeb\InstaURLs;
use ApiInstaWeb\InstaClient;
use ApiInstaWeb\VerificationChoice;
use ApiInstaWeb\Responses\LoginResponse;
use ApiInstaWeb\Exceptions\InstaException;
use ApiInstaWeb\Responses\CookiesResponse;
use ApiInstaWeb\Exceptions\CurlNertworkException;
use ApiInstaWeb\Exceptions\IncorrectPasswordException;
use ApiInstaWeb\Exceptions\InstaCheckpointRequiredException;
use business\CookiesRequest;

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
    require_once config_item('login-response-class');
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-insta_url-resource');
    require_once config_item('thirdparty-insta_client-resource');
    require_once config_item('thirdparty-verification_choice-resource');
    require_once config_item('insta_checkpoint_required-exception-class');
    require_once config_item('cookies-response-class');
    require_once config_item('business-cookies_request-class');

    $this->CI = &get_instance();
    $this->CI->load->model("db_model");

    $this->InstaClient = new InstaClient("", new CookiesRequest(null, null, null, null), new Proxy("", "", "", ""));
  }

  public function make_login(string $login, string $pass) {
    try {
      $result = $this->InstaClient->make_login($login, $pass);
    } 
    catch (InstaCheckpointRequiredException $e) {
      $result = new LoginResponse('ok', false, $e->getMessage(), NULL, $e->GetChallange());
    } 
    catch (InstaException $e) {
      $this->db_model->InsertEventToWashdog($Client->id, $e->getMessage(), $source);

      $result = new LoginResponse('ok', false, $e->getMessage(), NULL);
    }
    return $result;
  }
  
  public function make_insta_friendships_command(string $resource_id, string $command = 'follow', string $objetive_url = 'web/friendships') {

    $this->InstaClient->make_insta_friendships_command($resource_id, $command, $objetive_url);
  }

  public function make_curl_friendships_command_str(string $url) {

    $this->InstaClient->make_curl_friendships_command_str($url);
  }

  public function make_curl_chaining_str(string $insta_id, int $N, string $cursor = NULL) {

    $this->InstaClient->make_curl_chaining_str($insta_id, $N, $cursor);
  }

  public function obtine_cookie_value($cookies, string $name) {

    $this->InstaClient->obtine_cookie_value($cookies, $name);
  }

  public function get_cookies_value(string $key) {

    $this->InstaClient->get_cookies_value($key);
  }

  public function make_post() {

    $this->InstaClient->make_post();
  }

  public function get_insta_csrftoken($ch) {

    $this->InstaClient->get_insta_csrftoken($ch);
  }

  public function verify_cookies(\stdClass $cookies) {

    $this->InstaClient->verify_cookies($cookies);
  }

  public function like_first_post(string $fromClient_ista_id) {

    $this->InstaClient->like_first_post($fromClient_ista_id);
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
    echo __CLASS__."->".__FUNCTION__."() invocado (<b>ok</b>)";
  }

}
