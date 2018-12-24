<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  /**
   * @author:      Carlos R. Herrera Marquez <cherreram2012@gmail.com>
   * 
   * @description: define for CodeIgniter environment the paths of projects  
   *               resources.
   */

  $config['business-client-class'] = getcwd().'/application/business/Client.php';  
  $config['business-x-class'] = getcwd().'/application/business/x.php';
  $config['business-y-class'] = getcwd().'/application/business/y.php';
  $config['business-z-class'] = getcwd().'/application/business/z.php';
  
  $config['db-exception-class'] = getcwd().'/application/business/DB_Exception.php';
  
  
?>
