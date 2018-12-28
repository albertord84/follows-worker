<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\Proxy;
use ApiInstaWeb\GeoProfile;

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
      
    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  }

  protected function process_insta_prof_data(\stdClass $content) {
     $this->GeoProfile->process_insta_prof_data($content);
  }

  public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {
    /*$ApiInstaWeb = new ApiInstaWeb\GeoProfile();
    $ApiInstaWeb->get_insta_followers($cookies, $N, $cursor, $proxy);*/
  }

  public function get_insta_media(\stdClass $cookies = NULL, int $N = 15, string $cursor = NULL, Proxy $proxy = NULL) {
    /*$ApiInstaWeb = new ApiInstaWeb\GeoProfile();
    $ApiInstaWeb->get_insta_media($cookies, $N, $cursor, $proxy);*/
  }

  public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    /*$ApiInstaWeb = new ApiInstaWeb\GeoProfile();
    $ApiInstaWeb->get_post_user_info($post_reference, $cookies, $proxy);*/
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
