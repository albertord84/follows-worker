<?php

namespace InstaApiWeb\Exceptions {
  require_once 'ExceptionCode.php';
  require_once 'InstaException.php';
  /**
   * Description of InstaPasswordException
   *
   * @author jose
   */
  class InstaPasswordException extends InstaException {

    //put your code here
    public function __construct(string $message = "", Throwable $previous = null) {
      parent::__construct($message, ExceptionCode::InstaPassword, $previous);
    }

    public function Code() {
      return ExceptionCode::InstaPassword;
    }

  }

}