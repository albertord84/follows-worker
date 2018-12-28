<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\InstaURLs;
use ApiInstaWeb\InstaProfile;

/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class InstaProfile_lib {

  public function __construct ()
  {
    require_once config_item('thirdparty-insta_url-resource');
    require_once config_item('thirdparty-insta_profile-resource');

    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  }

  public function get_reference_user($cookies, $reference_user_name) {

  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
