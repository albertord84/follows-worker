<?php

namespace ApiInstaWeb\Exceptions {

  //require_once 'ExceptionCode.php';
  require_once 'InstaException.php';

  /**
   * Description of CookiesWrongSyntaxException
   *
   * @author jose
   */
  class CookiesWrongSyntaxException extends InstaException {
  //class CookiesWrongSyntaxException {
    //put your code here
    //public function __construct(string $message = "", $code, Throwable $previous = null) {
    public function __construct($message = "", $code, $previous = null) {
      //parent::__construct($message, ExceptionCode::CookiesWrongSyntax, $previous);
      parent::__construct($message, $code, $previous);
    }

    public function Code() {
      //return ExceptionCode::CookiesWrongSyntax;
    }
  }

}
