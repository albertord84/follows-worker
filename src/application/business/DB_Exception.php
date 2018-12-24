<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DB_Exception extends Exception {

  private $db_error;
  private $trace;
  
  public function __construct($db_error, $php_error, $file, $line, $trace, $code = 0, Exception $previous = null) {
    parent::__construct($php_error, $code, $previous);
    
    $this->db_error = $db_error;
    $this->file = $file;
    $this->line = $line;
    $this->trace = $trace;
  }

  public function getDB_error()
  {
    return '(code-'.$this->db_error['code'].'): '.$this->db_error['message'];
  }
  
  public function getErrorInfo ()
  {
    $strError = sprintf("<b>DB-Error: </b> %s<br>", $this->getDB_error());
    
    return $strError;
    //print_r($this->trace);
  }
}