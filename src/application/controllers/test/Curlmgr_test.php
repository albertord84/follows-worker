<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use InstaApiWeb\InstaCurlMgr;
use InstaApiWeb\EnumAction;
use InstaApiWeb\EnumEntity;
use business\Proxy;
use business\CookiesRequest;
use InstaApiWeb\Exceptions\InstaException;
use InstaApiWeb\Exceptions\InstaCurlMediaException;

class CurlMgr_test extends CI_Controller {

    public function __construct() {
    parent::__construct();

    require_once config_item('business-proxy-class');
    require_once config_item('business-cookies_request-class');
    require_once config_item('thirdparty-insta_curl_mgr-resource');
    require_once config_item('insta-curl-exception-class');
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
    
    //$str = "<br><br>mi nombre es: <b>%s</b>. Esta es una cadena formateada de forma avanzada";
    //$str = sprintf($str, "Carlos");    
    //echo $str;
    
    $id = 123;
    $N = 1;
    $cursor = "00-cursor-00";
    
    /*$variables = "{\"id\":\"$id\",\"first\":\"$N\"";
    //if ($cursor != NULL && $cursor != "NULL") {
      $variables .= ",\"after\":\"$cursor\"";
    //}
    $variables .= "}";
    
    echo "<br><br>".$variables."<br>"; echo urlencode($variables);*/
    
    $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::HASHTAG), new EnumAction(EnumAction::CMD_FOLLOW));
    $obj->setMediaData($id, $N, $cursor);
    //$obj->make_curl_str($proxy, $cookies);
    
    //$obj = new EnumAction(EnumAction::CMD_LOGIN);
    //echo "<br>".$obj;
  }

  public function run() {    
    try {
      echo "<h2>CLIENT + LIKE</h2>";
      $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::CLIENT), new EnumAction(EnumAction::CMD_LIKE)); 
      echo $obj->make_curl_str(new Proxy(), new CookiesRequest("", "", "", ""), "my-id-123");
      
      echo "<h2>GEO + GET_POST</h2>";
      $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::GEO), new EnumAction(EnumAction::GET_POST));         
      $obj->setMediaData("AAA", "111", "AA-cursor-11");
      echo $obj->make_curl_str(new Proxy(), new CookiesRequest("", "", "", ""));
      //var_dump($obj);
    
      echo "<h2>HASHTAG + GET_POST</h2>";
      $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::HASHTAG), new EnumAction(EnumAction::GET_POST));         
      $obj->setMediaData("BBB", "2", "BB-cursor-22");
      echo $obj->make_curl_str(new Proxy(), new CookiesRequest("", "", "", ""));
      //var_dump($obj);

      echo "<h2>PERSON + GET_POST</h2>";
      $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::PERSON), new EnumAction(EnumAction::GET_POST));         
      $obj->setMediaData("CCC", "3", "CC-cursor-33");
      echo $obj->make_curl_str(new Proxy(), new CookiesRequest("", "", "", ""));
      //var_dump($obj);      
      
      //$obj = new InstaProfileType(InstaProfileType::PERSON);
      //var_dump($obj);
    }
    catch (InstaException $e){
      echo "Exception Msg: ".$e->getMessage();
    }
  }

}
