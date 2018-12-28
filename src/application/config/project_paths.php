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
  
  $config['db-exception-class'] = getcwd().'/application/business/OwnException.php';
  
  $config['thirdparty-insta_api-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaApi.php';
  $config['thirdparty-a-resource'] = getcwd().'/application/third_party/APIInstaWeb/a.php';
  $config['thirdparty-b-resource'] = getcwd().'/application/third_party/APIInstaWeb/b.php';
  $config['thirdparty-c-resource'] = getcwd().'/application/third_party/APIInstaWeb/c.php';
  $config['thirdparty-d-resource'] = getcwd().'/application/third_party/APIInstaWeb/d.php';
?>
