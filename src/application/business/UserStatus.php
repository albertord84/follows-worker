<?php

namespace business {
  
   /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an UserStatus business static class.
   * 
   */
  class UserStatus {

    const ACTIVE = 1;
    const BLOCKED_BY_PAYMENT = 2;
    const BLOCKED_BY_INSTA = 3;
    const DELETED = 4;
    const INACTIVE = 5;
    const PENDING = 6;
    const UNFOLLOW = 7;
    const BEGINNER = 8;
    const VERIFY_ACCOUNT = 9;
    const BLOCKED_BY_TIME = 10;
    const DONT_DISTURB = 11;
    const DUMBU_UNFOLLOW = 12;
    const KEEP_UNFOLLOW = 13;

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