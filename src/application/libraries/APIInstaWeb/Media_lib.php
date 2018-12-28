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


    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
