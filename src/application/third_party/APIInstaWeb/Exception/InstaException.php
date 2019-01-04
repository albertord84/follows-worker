<?php

namespace ApiInstaWeb\Exceptions {
  //require_once 'ExceptionCode.php';

  /**
   * Description of InstaException
   *
   * @author dumbu
   */
 //class InstaException extends Exception {
  class InstaException {
    //private $code;

    //public function __construct(string $message = "", ApiInstaWeb\Exceptions\ExceptionCode $code, Throwable $previous = null) {
    public function __construct($message, $code, $previous){
      parent::__construct($message, $code, $previous);
      //parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
      //$this->code = $code;
    }

    //put your code here
    /*public function Code() {
      return $this->$code;
    }*/

  }

}