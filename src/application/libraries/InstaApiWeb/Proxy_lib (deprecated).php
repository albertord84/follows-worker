<?php defined('BASEPATH') OR exit('No direct script access allowed');

use InstaApiWeb\Proxy;

/**
 * @category CodeIgniter-Library: X
 * 
 * @access public
 *
 * @todo Define a codeigniter library for X
 * 
 */
class Proxy_lib {

  public function __construct ()
  {
    require_once config_item('thirdparty-proxy-resource');
    
     $this->Proxy = new Proxy("", "", "", ""); 
  }
  
  /*public function __construct(string $ip, string $port, string $user, string $password) {
    $this->ip = $ip;
    $this->port = $port;
    $this->user = $user;
    $this->password = $password;
  }*/

  public function ToString() {
    
    $this->Proxy->ToString();
 
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo __CLASS__."->".__FUNCTION__."() invocado (<b>ok</b>)";
  }
}
