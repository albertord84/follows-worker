<?php

defined('BASEPATH') OR exit('No direct script access allowed');

  
use InstaApiWeb\Proxy;
use InstaApiWeb\InstaURLs;
use InstaApiWeb\InstaClient;
use InstaApiWeb\VerificationChoice;
use InstaApiWeb\Responses\LoginResponse;
use InstaApiWeb\Exceptions\InstaException;
use InstaApiWeb\Responses\CookiesResponse;
use InstaApiWeb\Exceptions\CurlNertworkException;
use InstaApiWeb\Exceptions\IncorrectPasswordException;
use InstaApiWeb\Exceptions\InstaCheckpointRequiredException;
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
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-insta_url-resource');
    require_once config_item('thirdparty-login_response-class');
    require_once config_item('thirdparty-insta_client-resource');
    require_once config_item('thirdparty-cookies_response-class');
    require_once config_item('thirdparty-verification_choice-resource');
    require_once config_item('insta_checkpoint_required-exception-class');
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
