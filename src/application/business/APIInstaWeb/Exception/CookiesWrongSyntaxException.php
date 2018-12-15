<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb\Exceptions;

/**
 * Description of CookiesWrongSyntaxException
 *
 * @author jose
 */
class CookiesWrongSyntaxException extends \InstaException {
    //put your code here
    public function __construct(string $message = "", Throwable $previous = null) {
        parent::__construct($message, ExceptionCode::CookiesWrongSyntax, $previous);
    }
}
