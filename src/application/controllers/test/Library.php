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
    
    public function load ()
    {
      //$params = array('username'  => 'isela');
      
      $count = 0;
      echo "<h3><u>Test de carga de librerias</u></h3>";
      
      // -OK-
      echo "<pre>";
      echo "[load] GeoProfile_lib ==> ";
      $this->load->library("APIInstaWeb/GeoProfile_lib", null, 'GeoProfile_lib');
      echo "(<b>ok</b>) || ";
      $this->GeoProfile_lib->Msg();
      $count++;
      
      // -OK-
      echo "<br>[load] HashProfile_lib ==> ";
      $this->load->library("APIInstaWeb/HashProfile_lib", null, 'HashProfile_lib');
      echo "(<b>ok</b>) || ";
      $this->HashProfile_lib->Msg();
      $count++;
      
      // -OK-
      echo "<br>[load] InstaApi_lib ==> ";
      //$this->load->library("APIInstaWeb/InstaApi_lib", $params, 'InstaApi_lib');
      $this->load->library("APIInstaWeb/InstaApi_lib", null, 'InstaApi_lib');
      echo "(<b>ok</b>) || ";
      $this->InstaApi_lib->Msg();
      $count++;
      
      // -OK-
      echo "<br>[load] InstaClient_lib ==> ";
      //$this->load->library("APIInstaWeb/InstaClient_lib", null, 'InstaClient_lib');
      echo "(<b>ok</b>) || ";
      //$this->InstaClient_lib->Msg();
      //$result = new ApiInstaWeb\Responses\LoginResponse();
      //$result = $this->InstaClient_lib->make_login("alberto_test", "alberto2");
      //var_dump($result);
      $count++;
      
      // -OK-
      echo "<br>[load] PersonProfile_lib ==> ";
      //$this->load->library("APIInstaWeb/PersonProfile_lib", null, 'PersonProfile_lib');
      echo "(<b>ok</b>) || ";
      //echo "(<b>ok</b>) || ";
      //$this->PersonProfile_lib->Msg();
      $count++;
      
      // -OK-
      //$this->load->library("APIInstaWeb/InstaProfileList_lib", null, 'InstaProfileList_lib');
      //echo "(<b>ok</b>) || ";
      //$this->InstaProfileList_lib->Msg();
      $count++;
      
      // -medio OK.. revisar constructor-
      //$this->load->library("APIInstaWeb/InstaProfile_lib", null, 'InstaProfile_lib');
      //echo "(<b>ok</b>) || ";
      //$this->InstaProfile_lib->Msg();
      $count++;
      
      // -OK-
      //$this->load->library("APIInstaWeb/Proxy_lib", null, 'Proxy_lib');
      //echo "(<b>ok</b>) || ";
      //$this->Proxy_lib->Msg();
      $count++;
      
      //$this->load->library("APIInstaWeb/Media_lib", null, 'Media_lib');
      //echo "(<b>ok</b>) || ";
      //$this->Media_lib->Msg();
      $count++;
      
      echo "<br><br>total: ".$count." libs";
      echo "</pre>";
    }
    
}


