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
  
  $config['business-admin-class'] = getcwd().'/application/business/Admin.php';
  $config['business-attendent-class'] = getcwd().'/application/business/Attendent.php';
  $config['business-geo_profile-class'] = getcwd().'/application/business/BusinessGeoProfile.php';
  $config['business-hash_profile-class'] = getcwd().'/application/business/BusinessHashProfile.php';
  $config['business-person_profile-class'] = getcwd().'/application/business/BusinessPersonProfile.php';
  $config['business-ref_profile-class'] = getcwd().'/application/business/BusinessRefProfile.php';
  $config['business-class'] = getcwd().'/application/business/Business.php';
  $config['business-client-class'] = getcwd().'/application/business/Client.php';  
  $config['business-cookies_request-class'] = getcwd().'/application/business/CookiesRequest.php';
  $config['business-own_exception-class'] = getcwd().'/application/business/OwnException.php';
  $config['business-proxy-class'] = getcwd().'/application/business/Proxy.php';
  $config['business-proxy_manager-class'] = getcwd().'/application/business/ProxyManager.php';
  $config['business-status_profiles-class'] = getcwd().'/application/business/StatusProfiles.php';
  $config['business-user-class'] = getcwd().'/application/business/User.php';
  $config['business-system_config-class'] = getcwd().'/application/business/SystemConfig.php';
  $config['business-user_role-class'] = getcwd().'/application/business/UserRole.php';
  $config['business-user_status-class'] = getcwd().'/application/business/UserStatus.php';
  $config['business-washdog_type-class'] = getcwd().'/application/business/WashdogType.php';
   
  //Clases del negocio del Worker
  $config['business-daily_work-class'] = getcwd().'/application/business/Worker/DailyWork.php';
  $config['business-error_type-class'] = getcwd().'/application/business/Worker/ErrorType.php';
  $config['business-robot-class'] = getcwd().'/application/business/Worker/Robot.php';
  $config['business-worker-class'] = getcwd().'/application/business/Worker/Worker.php';
  
  //Clases del negocio del Response
  $config['business-dumbu_response-class'] = getcwd().'/application/business/SystemResponse/DUMBUResponse.php';
  $config['business-payment_response-class'] = getcwd().'/application/business/SystemResponse/PaymentResponse.php';
  $config['business-reference_profile_response-class'] = getcwd().'/application/business/SystemResponse/ReferenceProfileResponse.php';
  
/*
|--------------------------------------------------------------------------
| Paths de los recursos de terceros.
*/
  
  //Recursos de la InstaApiWeb que viraron tambien librerias (nombreRecurso_lib)
  $config['thirdparty-geo_profile-resource'] = getcwd().'/application/third_party/InstaApiWeb/GeoProfile.php';
  $config['thirdparty-has_profile-resource'] = getcwd().'/application/third_party/InstaApiWeb/HashProfile.php';
  $config['thirdparty-insta_api-resource'] = getcwd().'/application/third_party/InstaApiWeb/InstaApi.php';
  $config['thirdparty-insta_client-resource'] = getcwd().'/application/third_party/InstaApiWeb/InstaClient.php';
  $config['thirdparty-insta_profile_list-resource'] = getcwd().'/application/third_party/InstaApiWeb/InstaProfileList.php';
  $config['thirdparty-insta_profile-resource'] = getcwd().'/application/third_party/InstaApiWeb/InstaProfile.php';  
  $config['thirdparty-media-resource'] = getcwd().'/application/third_party/InstaApiWeb/Media.php';
  $config['thirdparty-person_profile-resource'] = getcwd().'/application/third_party/InstaApiWeb/PersonProfile.php';  
  $config['thirdparty-proxy-resource'] = getcwd().'/application/third_party/InstaApiWeb/Proxy.php';

  //Recursos solamente de la InstaApiWeb
  $config['thirdparty-insta_url-resource'] = getcwd().'/application/third_party/InstaApiWeb/InstaURLs.php';
  $config['thirdparty-profile_type-resource'] = getcwd().'/application/third_party/InstaApiWeb/ProfileType.php';  
  $config['thirdparty-verification_choice-resource'] = getcwd().'/application/third_party/InstaApiWeb/VerificationChoice.php'; 
 
  
/*
|--------------------------------------------------------------------------
| Paths de las Clases Exception
*/
  
  //Exception de la BD
  $config['db-exception-class'] = getcwd().'/application/business/OwnException.php';
  
  //Exception de la InstaApiWeb
  $config['cookies_wrong_syntax-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/CookiesWrongSyntaxException.php';
  $config['curl_nertwork-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/CurlNertworkException.php';
  $config['end_cursor-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/EndCursorException.php';
  $config['code-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/ExceptionCode.php';
  $config['incorrect_password-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/IncorrectPasswordException.php';
  $config['insta_checkpoint_required-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/InstaCheckpointRequiredException.php';
  $config['insta-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/InstaException.php';
  $config['wrong_end_cursor-exception-class'] = getcwd().'/application/third_party/InstaApiWeb/Exception/WrongEndCursorException.php';
  
  /*
|--------------------------------------------------------------------------
| Paths de las Clases Response de la InstaApiWeb
*/
  $config['client_response-class'] = getcwd().'/application/third_party/InstaApiWeb/Response/ClientResponse.php'; 
  $config['cookies-response-class'] = getcwd().'/application/third_party/InstaApiWeb/Response/CookiesResponse.php';
  $config['insta-response-class'] = getcwd().'/application/third_party/InstaApiWeb/Response/InstaResponse.php';
  $config['login-response-class'] = getcwd().'/application/third_party/InstaApiWeb/Response/LoginResponse.php';
  
  /*
|--------------------------------------------------------------------------
| Paths de las librerias Email y Payment
  */
  
  //Librerias de Email 
  $config['email-allin-libraries'] = getcwd().'/application/libraries/Email/Allin.php'; 
  $config['email-gmail-libraries'] = getcwd().'/application/libraries/Email/Gmail.php'; 
   
  //Librerias de Payment
  $config['payment-mundipagg-libraries'] = getcwd().'/application/libraries/Payment/Mundipagg.php'; 
  $config['payment-vindi-libraries'] = getcwd().'/application/libraries/Payment/Vindi.php'; 
   
   
  
?>

