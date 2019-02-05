<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use InstaApiWeb\CookiesRequest;

/**
 * Description of ReferenceProfile_lib
 *
 * @author jose
 */
abstract class ReferenceProfile_lib {

  public $ReferencePriofile;

  public function __construct() {
    require_once config_item('thirdparty-proxy-resource');
    require_once config_item('thirdparty-cookies');
  }

  public function process_insta_prof_data(\stdClass $content) {
    if ($this->ReferencePriofile == null)
      throw new Exception("Null reference exception in ReferenceProfile variable");
    return $this->ReferencePriofile->process_insta_prof_data($content);
  }

  public function get_insta_followers(\stdClass $cookies = NULL, int $N = 15, string& $cursor = NULL, Proxy $proxy = NULL) {
    if ($this->ReferencePriofile == null)
      throw new Exception("Null reference exception in ReferenceProfile variable");
    return $this->ReferencePriofile->get_insta_followers($cookies, $N, $cursor, $proxy);
  }

  public function get_insta_media(int $N, string $cursor = NULL, \stdClass $cookies = NULL, Proxy $proxy = NULL) {
    if ($this->ReferencePriofile == null) {
      throw new Exception("Null reference exception in ReferenceProfile variable");
    }
    $cookies = new CookiesRequest($cookies);
    return $this->ReferencePriofile->get_insta_media($N, $cursor, $cookies, $proxy);
  }

  public function get_post_user_info($post_reference, \stdClass $cookies = NULL, Proxy $proxy = NULL) {

    if ($this->ReferencePriofile == null)
      throw new Exception("Null reference exception in ReferenceProfile variable");
    return $this->ReferencePriofile->get_post_user_info($post_reference, $cookies, $proxy);
  }

}
