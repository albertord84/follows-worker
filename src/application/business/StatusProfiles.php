<?php

namespace business {

  require_once config_item('business-user-class');

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an StatusProfiles business class.
   * 
   */
  class StatusProfiles extends Business {

    public function __construct() {
      parent::__construct();

      $this->CI->load->model('db_model');
      $result = $this->CI->db_model->GetReferenceProfileStatus();
      if (count($result) != 0) {
        foreach ($result as $item) {
          $this->{$item->status} = $item->id;
        }
      } else {
        die("Can't load system config vars...!!");
      };
    }

    static public function Defines($const) {
      $cls = new ReflectionClass(__CLASS__);
      foreach ($cls->getConstants() as $key => $value) {
        if ($value == $const) {
          return true;
        }
      }
      return false;
    }

  }

}