<?php defined('BASEPATH') OR exit('No direct script access allowed');

 use ApiInstaWeb\InstaProfileList;
  
/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class InstaProfileList_lib {
  
  public function __construct ()
  {
    require_once config_item('thirdparty-insta_profile_list-resource');

    $this->InstaProfileList = new InstaProfileList();

    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  } 

  public function get_list_from_insta_follower_list($response) {

  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
