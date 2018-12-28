<?php

namespace ApiInstaWeb\Exceptions {
  //require_once 'ExceptionCode.php';

  /**
   * Description of InstaException
   *
   * @author dumbu
   */
  class InstaException extends Exception {

    private $code;

    //public function __construct(string $message = "", ApiInstaWeb\Exceptions\ExceptionCode $code, Throwable $previous = null) {
    public function __construct(){
    //parent::__construct($message, $previous);
      //$this->code = $code;
    }

    //put your code here
    public function Code() {
      return $this->$code;
    }

  }

}