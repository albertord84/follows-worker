<?php

namespace InstaApiWeb\Exceptions {
  
  require_once 'ExceptionCode.php';
  require_once 'InstaException.php';
  
  /**
   * Description of InstaCheckpointException
   *
   * @author jose
   */
  class InstaCheckpointException extends InstaException {

    //put your code here
    private $challange;

    public function __construct(string $message = "", Throwable $previous = null, string $challange_url = NULL) {
      parent::__construct($message, ExceptionCode::InstaCheckpointRequired, $previous);
      $this->challange = $challange_url;
    }

    public function Code() {
      return ExceptionCode::InstaCheckpointRequired;
    }

    public function GetChallange() {
      return $this->challange;
    }

  }

}


