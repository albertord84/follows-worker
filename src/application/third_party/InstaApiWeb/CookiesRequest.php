<?php

namespace InstaApiWeb {

  /**
   * @category InstaApiWeb class
   * 
   * @access public
   *
   * @todo Define an Client business class.
   * 
   */
  class CookiesRequest {

    /**
     * 
     * @access public
     * 
     */
    public $SessionId;
    
    /**
     * 
     * @access public
     * 
     */
    public $CsrfToken;
    
    /**
     * 
     * @access public
     * 
     */
    public $DsUserId;
    
    /**
     * 
     * @access public
     * 
     */
    public $Mid;
    
    // Asi casi no se usa, por PHP no tener sobrecarga pensaremos despues como hacer esto: Alberto
//    public function __construct(string $session_id, string $csrf_token, string $dsuser_id, string $mid) {          
//      $this->SessionId = $session_id;
//      $this->CsrfToken = $csrf_token;
//      $this->DsUserId = $dsuser_id;
//      $this->Mid = $mid;
//    }
    
    
    public function __construct(\stdClass $cookies) {          
      $this->SessionId = $cookies->sessionid;
      $this->CsrfToken = $cookies->csrftoken;
      $this->DsUserId = $cookies->ds_user_id;
      $this->Mid = $cookies->mid;
    }

  }

}
