<?php

namespace business {

  require_once config_item('business-user-class');

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an Proxy business class.
   * 
   */
  class Proxy extends Business {

    public $Id;
    public $Ip;
    public $Port;
    public $User;
    public $Password;
    public $IsReserved;

    function __construct() {
      parent::__construct();
      $this->CI->load->model('proxy_model');
    }

    public function load_from_db(int $id) {
        $proxy_data = $this->CI->proxy_model->get_by_id($id);
        
        $this->Id = $proxy_data->idProxy;
        $this->Ip = $proxy_data->proxy;
        $this->Port = $proxy_data->port;
        $this->User = $proxy_data->proxy_user;
        $this->Password = $proxy_data->proxy_password;
        $this->IsReserved = $proxy_data->isReserved;
    }

    public function save_to_db() {
      
    }
  }

}    