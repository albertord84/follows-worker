<?php

namespace InstaApiWeb\Exceptions {

  /**
   * Description of CurlNertworkException
   *
   * @author jose
   */
  class InstaCurlNetworkException extends InstaException {

    public function __construct(string $message = "", Throwable $previous = null) {
      parent::__construct($message, ExceptionCode::CurlNertworkError, $previous);
    }

    //put your code here
  }

}