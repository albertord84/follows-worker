<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use business\Client;

class Library extends CI_Controller {
    public function __construct() {
      parent::__construct() ;
      
      require_once config_item('db-exception-class');
      require_once config_item('business-client-class');
    }
    
    public function index() {
      echo "Controller: <b>".__CLASS__."</b> cargado.";
    }
    
    public function client() {
      echo "Dentro de client controller";
      
      $obj = new Client();
      
      echo "<br><br>Ok";
    }
    
    public function library ()
    {
      //$params = array('username'  => 'isela');
       
      // -OK-
      //$this->load->library("APIInstaWeb/GeoProfile_lib", null, 'GeoProfile');
      //$this->GeoProfile_lib->Msg();
      //$this->GeoProfile->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/HashProfile_lib", null, 'HashProfile_lib');
      //$this->HashProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/InstaApi_lib", $params, 'InstaApi_lib');
      //$this->load->library("APIInstaWeb/InstaApi_lib", null, 'InstaApi_lib');
      //$this->InstaApi_lib->Msg();
      
      // -OK-
      echo 'ok';
      $this->load->library("APIInstaWeb/InstaClient_lib", null, 'InstaClient_lib');
      $result = new ApiInstaWeb\Responses\LoginResponse();
      $result = $this->InstaClient_lib->make_login("alberto_test", "alberto2");
      var_dump($result);
      
      // -OK-
      //$this->load->library("APIInstaWeb/PersonProfile_lib", null, 'PersonProfile_lib');
      //$this->PersonProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/InstaProfileList_lib", null, 'InstaProfileList_lib');
      //$this->InstaProfileList_lib->Msg();
      
      // -medio OK.. revisar constructor-
      //$this->load->library("APIInstaWeb/InstaProfile_lib", null, 'InstaProfile_lib');
      //$this->InstaProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/Proxy_lib", null, 'Proxy_lib');
      //$this->Proxy_lib->Msg();
      
      //$this->load->library("APIInstaWeb/Media_lib", null, 'Media_lib');
      //$this->Media_lib->Msg();
   
    }
    
}


