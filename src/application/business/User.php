<?php

namespace business {

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
    protected $id;

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
