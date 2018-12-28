<?php defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for InstaApiWeb
 * 
 */
class InstaApi_lib {

    public function __construct ()
    {
      require_once config_item('thirdparty-X-resource');
     
      
      echo "se cargo satisfactoriamente la libreria<br><br>";
    }
    
    public function Msg ()
    {
      echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."</h2>";
    }
}
