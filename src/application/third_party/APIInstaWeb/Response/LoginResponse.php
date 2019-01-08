<?php

namespace ApiInstaWeb\Responses {

  /**
   * @category ApiInstaWeb Third-Party Class
   * 
   * @access public
   *
   * @todo Define a Instagram Login Response.
   * 
   */
  class LoginResponse {

    /**
     * 
     * @access public
     */
    public $Verify_link;
    
    /**
     * 
     * @access public
     */
    public $Authenticated;
    
    /**
     * 
     * @access public
     */
    public $Status;
    
    /**
     * 
     * @access public
     */
    public $Message;
    
    /**
     * 
     * @access public
     */
    public $Cookies;

    public function __construct($status = NULL, $authenticated = NULL, $message = NULL, CookiesResponse $cookies = NULL, $verify_link = NULL) {
      $this->Verify_link = $verify_link;
      $this->Authenticated = $authenticated;
      $this->Status = $status;
      $this->Message = $message;
      $this->Cookies = $cookies;
    }

    public function get_response() {
      
    }

  }

}
