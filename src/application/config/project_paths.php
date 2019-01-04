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
  
  //Recursos de la APIInstaWeb que viraron tambien librerias (nombreRecurso_lib)
  $config['thirdparty-geo_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/GeoProfile.php';
  $config['thirdparty-has_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/HashProfile.php';
  $config['thirdparty-insta_api-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaApi.php';
  $config['thirdparty-insta_client-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaClient.php';
  $config['thirdparty-insta_profile_list-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaProfileList.php';
  $config['thirdparty-insta_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaProfile.php';  
  $config['thirdparty-media-resource'] = getcwd().'/application/third_party/APIInstaWeb/Media.php';
  $config['thirdparty-person_profile-resource'] = getcwd().'/application/third_party/APIInstaWeb/PersonProfile.php';  
  $config['thirdparty-proxy-resource'] = getcwd().'/application/third_party/APIInstaWeb/Proxy.php';

  //Recursos solamente de la APIInstaWeb
  $config['thirdparty-insta_url-resource'] = getcwd().'/application/third_party/APIInstaWeb/InstaURLs.php';
  $config['thirdparty-profile_type-resource'] = getcwd().'/application/third_party/APIInstaWeb/ProfileType.php';  
  $config['thirdparty-verification_choice-resource'] = getcwd().'/application/third_party/APIInstaWeb/VerificationChoice.php'; 
 
  
/*
|--------------------------------------------------------------------------
| Paths de las Clases Exception.
*/
  
  //Exception de la BD
  $config['db-exception-class'] = getcwd().'/application/business/OwnException.php';
  
  //Exception de la APIInstaWeb
  $config['cookies_wrong_syntax-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/CookiesWrongSyntaxException.php';
  $config['curl_nertwork-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/CurlNertworkException.php';
  $config['end_cursor-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/EndCursorException.php';
  $config['code-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/ExceptionCode.php';
  $config['incorrect_password-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/IncorrectPasswordException.php';
  $config['insta_checkpoint_required-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/InstaCheckpointRequiredException.php';
  $config['insta-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/InstaException.php';
  $config['wrong_end_cursor-exception-class'] = getcwd().'/application/third_party/APIInstaWeb/Exception/WrongEndCursorException.php';
  
  /*
|--------------------------------------------------------------------------
| Paths de las Clases Response.
*/
  
  $config['insta-response-class'] = getcwd().'/application/third_party/APIInstaWeb/Response/InstaResponse.php';
  
?>

