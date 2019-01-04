<?php

namespace ApiInstaWeb\Exceptions {

  require_once 'ExceptionCode.php';
  require_once 'InstaException.php';

  /**
   * Description of CookiesWrongSyntaxException
   *
   * @author jose
   */
  class CookiesWrongSyntaxException extends InstaException {

    public function __construct($message = "", Throwable $previous = null) {
      parent::__construct($message, ExceptionCode::CookiesWrongSyntax, $previous);
    }

    public function Code() {
      return ExceptionCode::CookiesWrongSyntax;
    }
  }

}
