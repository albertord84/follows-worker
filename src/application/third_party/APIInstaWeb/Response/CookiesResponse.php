<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb\Responses{

    /**
     * Description of InstaResponse
     *
     * @author dumbu
     */
    class CookiesResponse {
      public $verify_link;
      public $authenticated;
      public $status;
      public $message;
      public $cookies;
      
      public function __construct($status = NULL, $authenticated = NULL, $message = NULL, $cookies = NULL, $verify_link = NULL) {
        $this->verify_link = $verify_link;
        $this->authenticated = $authenticated;
        $this->status = $status;
        $this->message = $message;
        $this->cookies = $cookies;
      }
      
      public function get_response() {
                
      }
    }
}
