<?php

namespace InstaApiWeb\Exceptions {
  /**
   * Description of ExceptionCode
   * Enum of 
   * @author dumbu
   */
  //class ExceptionCode extends SplEnum {
  class ExceptionCode {

    const __default = self::UnknownException;
    const UnknownException = 0;
    const CookiesWrongSyntax = 1;
    const InstaCheckpointRequired = 2;
    const IncorrectPassword = 3;
    const EndCursor = 4;
    const WrongEndCursor = 5;
    const CurlNertworkError = 28;

  }

}
