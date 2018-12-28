<?php defined('BASEPATH') OR exit('No direct script access allowed');

use ApiInstaWeb\Proxy;
use ApiInstaWeb\InstaApi;
use ApiInstaWeb\InstaURLs;

/**
 * @category CodeIgniter-Library: InstaApiLib
 * 
 * @access public
 *
 * @todo Define a codeigniter library for InstaApiWeb
 * 
 */
class InstaApi_lib {

  public function __construct ($params)
  {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-insta_api-resource');
    require_once config_item('thirdparty-insta_url-resource');

    $this->ApiInsta = new InstaApi();
    
    // Borrar luego, se usa para comprobar que se cargo bien el recurso.
    echo "se cargo satisfactoriamente la libreria: ".__CLASS__."<br><br>valor del parametro: ".$params['username'];
  }

  public function login(string $username, string $password, Proxy $proxy) {
    $this->ApiInsta->login($username, $password, $proxy);
  }

  public static function make_query(string $query, string $variables, \stdClass $cookies, Proxy $proxy = NULL) {
  }

  // Funcion temporal para comprobar que se cargo la lib.
  public function Msg ()
  {
    echo "<h2>se invoco bien un metodo de la lib: ".__CLASS__."->".__FUNCTION__."()</h2>";
  }
}
