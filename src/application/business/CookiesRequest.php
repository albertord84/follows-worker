<?php

namespace business {

  /**
   * @category Business class
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
    
    
    public function __construct(string $session_id, string $csrf_token, string $dsuser_id, string $mid) {          
      $this->SessionId = $session_id;
      $this->CsrfToken = $csrf_token;
      $this->DsUserId = $dsuser_id;
      $this->Mid = $mid;
    }

  }

}
