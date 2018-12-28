<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  /**
   * @author:      Carlos R. Herrera Marquez <cherreram2012@gmail.com>
   * 
   * @description: define for CodeIgniter environment the paths of projects  
   *               resources.
   */
   
/*
|--------------------------------------------------------------------------
| Paths de las clases del Negocio.
*/
  $config['business-client-class'] = getcwd().'/application/business/Client.php';  
  $config['business-x-class'] = getcwd().'/application/business/x.php';
  $config['business-y-class'] = getcwd().'/application/business/y.php';
  $config['business-z-class'] = getcwd().'/application/business/z.php';
  
/*
|--------------------------------------------------------------------------
| Paths de los recursos de terceros.
*/
  $config['thirdparty-geo_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/GeoProfile.php';
  $config['thirdparty-has_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/HashProfile.php';
  $config['thirdparty-insta_api-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaApi.php';
  $config['thirdparty-insta_client-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaClient.php';
  $config['thirdparty-insta_profile_list-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaProfileList.php';
  $config['thirdparty-insta_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaProfile.php';  
  $config['thirdparty-media-resource'] = getcwd().'/application/third_party/APIInstaWeb/Media.php';
  $config['thirdparty-person_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/PersonProfile.php';  
  $config['thirdparty-proxy-resource'] = getcwd().'/application/third_party/APIInstaWeb/Proxy.php';

  $config['thirdparty-insta_url-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaURLs.php';
  $config['thirdparty-profile_type-resource'] = getcwd().'/application/third_party/APIInstaWeb/ProfileType.php';  
  $config['thirdparty-verification_choice-resource'] = getcwd().'/application/third_party/APIInstaWeb/VerificationChoice.php'; 
 
  
/*
|--------------------------------------------------------------------------
| Paths de los Clases Exception.
*/
  $config['db-exception-class'] = getcwd().'/application/business/OwnException.php';
  $config['cookies_wrong_syntax-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/CookiesWrongSyntaxException.php';

  $config['a-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/a.php';
  $config['b-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/b.php';
  $config['c-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/c.php';
  $config['d-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/d.php';
  $config['e-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/e.php';
?>

