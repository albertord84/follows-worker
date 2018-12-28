<?php defined('BASEPATH') OR exit('No direct script access allowed');

//require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';

use \ApiInstaWeb\InstaApi;
use \business\cls\Proxy;

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
      require_once config_item('thirdparty-insta_api-resource');
     
      $this->ApiInsta = new InstaApi();
      echo "se cargo satisfactoriamente la libreria<br><br>";
      //if (file_exists(config_item('thirdparty-insta_api-resource'))) echo "el fichero existe";
    }
    
    public function login(string $username, string $password, Proxy $proxy) {
        $this->ApiInsta->login($username, $password, $proxy);
    }

    public static function make_query(string $query, string $variables, \stdClass $cookies, Proxy $proxy = NULL) {
    }
    
    public function Msg ()
    {
      echo "<h2>se invoco bien un metodo de la lib</h2>";
    }
}
