<?php defined('BASEPATH') OR exit('No direct script access allowed');

use InstaApiWeb\Proxy;
use InstaApiWeb\GeoProfile;

/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class GeoProfile_lib {
  
  public function __construct() {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-geo_profile-resource');
    
    $this->GeoProfile = new GeoProfile();
  }

  public function process_insta_prof_data(\stdClass $content) {
     $this->GeoProfile->process_insta_prof_data($content);
  }

  public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {
     $this->GeoProfile->get_insta_followers($cookies, $N, $cursor, $proxy);
  }

  public function get_insta_media(int $N, string $cursor = NULL, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    $this->GeoProfile->get_insta_media($N, $cursor, $cookies, $proxy);
  }

  public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    $this->GeoProfile->get_post_user_info($post_reference, $cookies, $proxy);
  }

}
