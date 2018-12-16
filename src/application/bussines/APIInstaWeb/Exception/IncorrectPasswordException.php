<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb\Exceptions;

/**
 * Description of IncorrectPasswordException
 *
 * @author jose
 */
class IncorrectPasswordException extends \InstaException{
    //put your code here
    public function Code(){return ExceptionCode::IncorrectPassword; }
}
