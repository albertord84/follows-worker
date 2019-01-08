<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @category CodeIgniter-Library: Media_lib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for Media
 * 
 */
class Media_lib {

  public function __construct ()
  {
    //require_once config_item('thirdparty-X-resource');
    $this->Media = new Media();
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo __CLASS__."->".__FUNCTION__."() invocado (<b>ok</b>)";
  }
}
