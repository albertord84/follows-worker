<?php

namespace InstaApiWeb{

  /**
   * Description of ProfileType
   *
   * @author jose
   */
  //class ProfileType  extends SplEnum{
    class ProfileType{

       const __default = self::Person;
       const GeoReference = 1;
       const Person = 0;  
       const Tag = 2;
  }

}