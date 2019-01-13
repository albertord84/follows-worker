<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\HashProfile;
use ApiInstaWeb\Proxy;

/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class HashProfile_lib {
  
  public function __construct ()
  {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-has_profile-resource');
    
    $this->HashProfile = new HashProfile();
  }
   
  public function process_insta_prof_data(\stdClass $content) {
    $this->HashProfile->process_insta_prof_data($content);
  }

  public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
    $this->HashProfile->get_insta_followers($cookies, $N, $cursor, $proxy);
  }

  public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, \business\cls\Proxy $proxy = NULL) {
    $this->HashProfile->get_insta_media($cookies, $N, $cursor, $proxy);
  }

  public function get_post_user_info($post_reference, \stdClass $cookies = NULL, \business\cls\Proxy $proxy = NULL) {
    $this->HashProfile->get_post_user_info($post_reference, $cookies, $proxy);
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo __CLASS__."->".__FUNCTION__."() invocado (<b>ok</b>)";
  }
}
