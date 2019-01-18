<?php

namespace InstaApiWeb\Exceptions {
  require_once 'ExceptionCode.php';
  require_once 'InstaException.php';
  /**
   * Description of IncorrectPasswordException
   *
   * @author jose
   */
  class IncorrectPasswordException extends InstaException {

    //put your code here
    public function __construct(string $message = "", Throwable $previous = null) {
      parent::__construct($message, ExceptionCode::IncorrectPassword, $previous);
    }

    public function Code() {
      return ExceptionCode::IncorrectPassword;
    }

  }

}