<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Welcome extends CI_Controller {
    public function __construct() {
      parent::__construct() ;
      //require_once config_item('business-client-class');  
    }
    
    public function index() {
        echo "se invoco el index";
        /*var_dump("olaaaa");
        var_dump($_SERVER['DOCUMENT_ROOT']);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/src/application/business/Client.php';
        $C = new business\cls\Client(1);
        $C->change_status(1);*/
    }

    public function access_client_class ()
    {     
      $obj = new Client(99);
      echo "<h2>ACESO VIA CLASE DE TERCERO</h2>";
      echo "<u><b>invocado action-login desde client_BUSINESS</b></u>: ";
      $obj->make_login();
      
      echo "<u><b>acesando al client_MODEL desde client_BUSINESS</b></u><br>";
      $obj->change_status(10);
    }
    
    public function access_client_lib ()
    {
        echo "<h2>ACESO VIA LIBRERIA DE CODEIGNITER</h2>";
      echo("Estoy dentro de access_client_lib");  
    }

    public function test() {
        /*$this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/client_model');
        $gateway_client_id = 7397972;
        $client_id = $this->client_model->get_client_id_by_gateway_client_id($gateway_client_id);
        var_dump($client_id);
        $gateway_payment_key = 4875103;
        $client_id = $this->client_model->get_client_id_by_gateway_payment_key($gateway_payment_key);
        var_dump($client_id);*/
    }

}
