<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*class MY_Exceptions extends CI_Exceptions {
//class MY_Exceptions extends Exceptions {

  /*
   function MY_Exceptions(){
        parent::CI_Exceptions();
        
    }
  */
  
  /*public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }*/

  /*public function __construct()
  {
    parent::__construct(); 
    //set_exception_handler(array('MY_Exceptions', 'my_exception_handler'));
  }
  
  public function show_error($heading, $message, $template = 'error_general', $status_code = 500) {
    log_message('debug', print_r($message, TRUE));
    throw new Exception(is_array($message) ? $message[1] : $message, $status_code);
  }

  public function show_miError ()
  {
    echo $this->message;
  }
}

/*class MyDBException extends MY_Exceptions {
  
} *///define your exceptions

class DBExceptions extends Exceptions {
  
  /*public function __construct($message, $code = 0, Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }*/

  private $msg;
  
  public function __construct($msg)
  {
    parent::__construct(); 
    $this->msg = $msg;
  }
}