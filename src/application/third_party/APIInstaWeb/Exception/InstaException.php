<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InstaException
 *
 * @author dumbu
 */
class InstaException extends Exception{
    
    private $code;
    
    public function __construct(string $message = "",ApiInstaWeb\Exceptions\ExceptionCode $code, Throwable $previous = null) {
        parent::__construct($message,  $previous);
        $this->code = $code; 
    }
    //put your code here
    public function Code(){ return $code;}
    
 }
