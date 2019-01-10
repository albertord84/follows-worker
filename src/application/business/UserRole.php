<?php

namespace business {

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an UserRole business static class.
   * 
   */
  class UserRole {

    const ADMIN = 1;
    const CLIENT = 2;
    const ATTENDET = 3;

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