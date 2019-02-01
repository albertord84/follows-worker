<?php

namespace business {

  use stdClass;
  
  require_once config_item('business-class');
  require_once config_item('business-user_role-class');
  require_once config_item('business-user_status-class');
  
  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an User business class.
   * 
   */
  class User extends Business {

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $Id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Role_id;
    
     /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Name;
    
    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $Login;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Pass;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Email;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Phone_ddi;
    
     /**
     * 
     * @access public
     * @var type 
     * 
     */    
    public $Phone_ddd;
    
     /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Phone_number;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Status_id;

     /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Status_date;
    
    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $Languaje;

     /**
     * 
     * @access public
     * @var type
     *  
     */
    public $Init_date;
    
     /**
     * 
     * @access public
     * @var type
     *  
     */
    public $End_date;
    
    /**
     * 
     * @todo Class constructor.
     * 
     */
    function __construct() {
      parent::__construct();
      
      $ci = &get_instance();
      $ci->load->model('users_model'); 
    }

    public function load_data(int $id) {
      $ci = &get_instance();
      $data = $ci->users_model->get_by_id($id);
      
      $this->fill_data($data);
    }
    
    private function fill_data(stdClass $data) {
      $this->Id = $data->id;
      $this->Role_id = $data->role_id;
      $this->Name = $data->name;
      $this->Login = $data->login;
      $this->Pass = $data->pass;
      $this->Email = $data->email;
      $this->Phone_ddi = $data->phone_ddi;
      $this->Phone_ddd = $data->phone_ddd;
      $this->Phone_number = $data->phone_number;
      $this->Status_id = $data->status_id;
      $this->Status_date = $data->status_date;
      $this->Languaje = $data->languaje;
      $this->Init_date = $data->init_date;
      $this->End_date = $data->end_date;
    }
    
    public function id($value = NULL) {
      if (isset($value)) {
        $this->id = $value;
      } else {
        return $this->id;
      }
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function do_login($user_name, $user_pass) {
      echo $user_name;
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function update_user() {
      
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function disable_account() {
      
    }

  }

}
