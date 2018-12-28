<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb;

/**
 * Description of VerificationChoice
 *
 * @author jose
 */
class VerificationChoice  extends SplEnum{
     const __default = self::Email;
    
     const Email = 1;
     
     const SMS = 0;     
}
