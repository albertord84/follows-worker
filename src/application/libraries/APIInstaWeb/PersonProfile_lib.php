<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\Proxy;
use ApiInstaWeb\ProfileType;
use ApiInstaWeb\PersonProfile;

/**
 * @category CodeIgniter-Library: X
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class PersonProfile_lib {

  public function __construct ()
  {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-profile_type-resource');
    require_once config_item('thirdparty-person_profile-resource');
    
    $this->PersonProfile = new PersonProfile();
   
    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  }

  protected function process_insta_prof_data(\stdClass $content) {
    
    $this->PersonProfile->process_insta_prof_data($content); 
 
  }

  public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->get_insta_followers($cookies, $N, $cursor, $proxy);

  }

  private function get_insta_followers_list(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->get_insta_followers_list($cookies, $N,  $cursor, $proxy);

  }

  public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->get_insta_media($cookies, $N, $cursor, $proxy);
    
  }

  public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->get_post_user_info($post_reference, $cookies, $proxy);
    
  }

  protected function make_curl_following_str(\stdClass $cookies, int $N, string $cursor = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->make_curl_following_str($cookies, $N, $cursor, $proxy);
    
  }

  private function parse_follow_count($follow_count_str) {
    
    $this->PersonProfile->parse_follow_count($follow_count_str);
    
  }

  public function get_insta_following_count() {
    
    $this->PersonProfile->get_insta_following_count();
    
  }

  public function get_reference_data(\stdClass $cookies, string $referense_name) {
    
    $this->PersonProfile->get_reference_data($cookies, $referense_name);
    
  }

  public function exists_profile(string $profile_name, ProfileType $type, string $insta_id = NULL, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    
    $this->PersonProfile->exists_profile($profile_name, $type, $insta_id, $cookies, $proxy); 
    
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
