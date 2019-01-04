<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
    
     $this->Proxy = new Proxy(); 
    
    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>";
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
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
