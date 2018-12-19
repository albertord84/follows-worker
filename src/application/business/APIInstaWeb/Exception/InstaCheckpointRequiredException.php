<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb\Exceptions;

/**
 * Description of InstaCheckpointRequiredException
 *
 * @author jose
 */
class InstaCheckpointRequiredException extends \InstaException {
    //put your code here
    private $challange;
    
    public function __construct(string $message = "", Throwable $previous = null, string $challange_url = NULL) {
        parent::__construct($message, ExceptionCode::InstaCheckpointRequired, $previous);
        $this->challange = $challange_url;
    }
    
    
    public function Code(){ return ExceptionCode::InstaCheckpointRequired;}
    
    public function GetChallange(){ return $challange;}
}
