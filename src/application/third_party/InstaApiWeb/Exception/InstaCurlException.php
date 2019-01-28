<?php

namespace InstaApiWeb\Exceptions {
  
  require_once 'InstaException.php';
  
  /**
   * @category Third-Party InstaApiWeb Exception
   * 
   * @access public
   *
   * @todo Define Instagram Curl Exception.
   * 
   */
  class InstaCurlArgumentException extends InstaException {

    public function __construct(string $message, int $code = 0, Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
    }
    
  }
  
  /**
   * @category Third-Party InstaApiWeb Exception
   * 
   * @access public
   *
   * @todo Define Instagram Curl Exception.
   * 
   */
  class InstaCurlActionException extends InstaException {

    public function __construct(string $message, int $code = 0, Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
    }
    
  }
  
  /**
   * @category Third-Party InstaApiWeb Exception
   * 
   * @access public
   *
   * @todo Define Instagram Curl Exception.
   * 
   */
  class InstaCurlMediaException extends InstaException {

    public function __construct(string $message, int $code = 0, Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
    }
    
  }
 
  /**
   * @category Third-Party InstaApiWeb Exception
   * 
   * @access public
   *
   * @todo Define Instagram Curl Exception.
   * 
   */
  class InstaCurlChallengeException extends InstaException {

    public function __construct(string $message, int $code = 0, Throwable $previous = null) {
      parent::__construct($message, $code, $previous);
    }
    
  }
  
   /**
   * @category Third-Party InstaApiWeb Exception
   * 
   * @access public
   *
   * @todo Define Instagram Curl Exception.
   * 
   */
  class InstaCurlNetworkException extends InstaException {

    public function __construct(string $message = "", Throwable $previous = null) {
      parent::__construct($message, ExceptionCode::CurlNertworkError, $previous);
    }

    //put your code here
  }
}