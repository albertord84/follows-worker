<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ReferenceProfile_lib
 *
 * @author jose
 */
class ReferenceProfile_lib {
  
 public $ReferencePriofile;
  
 public function __construct() {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-cookies');    
  }

  
}
