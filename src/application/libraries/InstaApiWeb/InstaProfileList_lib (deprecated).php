<?php defined('BASEPATH') OR exit('No direct script access allowed');

 use InstaApiWeb\InstaProfileList;
  
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
  } 

  public function get_list_from_insta_follower_list($response) {
   
    $this->InstaProfileList->get_list_from_insta_follower_list($response);
    
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo __CLASS__."->".__FUNCTION__."() invocado (<b>ok</b>)";
  }
}
