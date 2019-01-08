<?php

namespace business {

  /**
   * @category Business Class
   * 
   * @access public
   *
   * @todo Define business base class.
   * 
   */
  class Business {

    /**
     * 
     * @access public
     */
    protected $CI;

    public function __construct() {
      $this->CI = &get_instance();
    }

    public function TEST($param) {
      
    }

  }

}
