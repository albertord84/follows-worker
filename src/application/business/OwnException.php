<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Db_Exception extends Exception {

  private $trace;
  private $db_error;
  
  public function __construct($db_error, Error $e) {
    parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
    
    $this->db_error = $db_error;
    $this->file = $e->getFile();
    $this->line = $e->getLine();
    $this->trace = $e->getTrace();
  }

  public function getDB_error()
  {
    return '(code-'.$this->db_error['code'].'): '.$this->db_error['message'];
  }
  
  public function getErrorInfo ()
  {
    $strError = sprintf("<b>db-error:</b> %s<br><br><b>php-error:</b> %s<br><b>file:</b> %s<br><b>line:</b> %s<br><b>code:</b> %d<br>",
                        $this->getDB_error(), $this->getMessage(), $this->file, $this->line, $this->code);
    
    $strTrace = "<br><b>-Trace error-</b><br>";
    foreach ($this->trace as $t) {
      $strTrace = sprintf("%s<b>file: </b>%s || <b>line: </b>%s || <b>function: </b>%s || <b>class: </b>%s<br>", 
                          $strTrace, $t['file'], $t['line'], $t['function'], (($t['class'] != "") ? $t['class'] : "none"));
    }
    
    return "<pre>".$strError.$strTrace."</pre>";
  }
}

class Php_Exception extends Exception {
  public function __construct(Error $e) {
    parent::__construct($e->getMessage(), $e->getCode(), $e->getPrevious());
    
    $this->file = $e->getFile();
    $this->line = $e->getLine();
    $this->trace = $e->getTrace();
  }
}