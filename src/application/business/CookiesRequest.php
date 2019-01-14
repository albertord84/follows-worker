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
    public $Session_id;
    
    /**
     * 
     * @access public
     * 
     */
    public $Csrf_Token;
    
    /**
     * 
     * @access public
     * 
     */
    public $DsUser_id;
    
    /**
     * 
     * @access public
     * 
     */
    public $Mid;
    
    
    public function __construct($session_id, $csrf_token, $dsuser_id, $mid) {          
      $this->Session_id = $session_id;
      $this->Csrf_Token = $csrf_token;
      $this->DsUser_id = $dsuser_id;
      $this->Mid = $mid;
    }

  }

}
