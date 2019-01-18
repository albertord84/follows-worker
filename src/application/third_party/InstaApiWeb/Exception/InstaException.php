<?php

namespace InstaApiWeb\Exceptions {

  use Exception;
  require_once 'ExceptionCode.php';

  /**
   * Description of InstaException
   *
   * @author dumbu
   */
  class InstaException extends Exception {

    public function __construct(string $message, int $code, Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
      $this->code = $code;
    }

    //put your code here
    public function Code() {
      return $this->code;
    } 
  }

}