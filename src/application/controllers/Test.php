<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
    public function __construct() {
      parent::__construct() ;
      //require_once config_item('business-client-class');  
    }
    
    public function index() {
    }
    
    public function entity ($param, $action, $id)
    {
      echo $param." | ".$action."<br>";
      
      if ($param == 'proxy') $this->e_proxy($action, $id);
    }
    
    private function e_proxy($action, $id)
    {
      
      $this->load->model('proxy_model');
      //$this->load->model('proxy_model', 'proxy');
      
      if ($action == 'get-by-id')
      {
        $items = $this->proxy_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->proxy_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
}


