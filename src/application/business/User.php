<?php

namespace business {

  require_once config_item('business-class');
  //require_once config_item('user_role');
  //require_once config_item('user_status');
  
  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an client business class.
   * 
   */
  class User extends Business {

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $id;

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $name;

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $login;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $pass;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $email;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $telf;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $role_id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $status_id;

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $languaje;

    /**
     * 
     * @todo Class constructor.
     * 
     */
    function __construct() {
      parent::__construct();

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
    public function load_user($user_id = 0) {
      
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
