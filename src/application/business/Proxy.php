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

    protected $id = 1;
    protected $proxy;
    protected $proxy_user;
    protected $proxy_password;
    protected $port;
    protected $isreserved;

    function __construct() {
      parent::__construct();
      $this->CI->load->model('proxy_model');
    }

    function load(int $id = NULL) {
      /*  $this->id = ($id) ? $id : $this->id;
        $proxy_data = $this->CI->proxy_model->get_by_id($this->id);
        $this->id = $id;
        $this->ip = $proxy_data[$ip];
        $this->port = $proxy_data[$port];
        $this->user = $proxy_data[$user];
        $this->password = $proxy_data[$password]; */
    }

  }

}    