<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb;

/**
 * Description of ProfileType
 *
 * @author jose
 */
class ProfileType  extends SplEnum{
     const __default = self::Person;
    
     const GeoReference = 1;
     
     const Person = 0;  
     
     const Tag = 2;
}
