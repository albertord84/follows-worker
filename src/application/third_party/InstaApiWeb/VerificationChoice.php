<?php

namespace InstaApiWeb {

  /**
   * Description of VerificationChoice
   *
   * @author jose
   */
  //class VerificationChoice  extends SplEnum{
  class VerificationChoice {
     const __default = self::Email;  
     const Email = 1;
     const SMS = 0;     
  }
  
}