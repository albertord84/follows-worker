<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace ApiInstaWeb\Exceptions{

/**
 * Description of ExceptionCode
 * Enum of 
 * @author dumbu
 */
class ExceptionCode extends SplEnum{
     const __default = self::UnknownException;
    
     const UnknownException = 0;
}


}