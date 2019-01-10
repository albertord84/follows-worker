<?php

namespace business\worker{

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an ErrorType worker class.
   * 
   */
     class ErrorType extends SplEnum{
    
       const __default = self::UnknownError;
    
       const UnknownError = 0;
   }
   
}   
