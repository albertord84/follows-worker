<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use InstaApiWeb\InstaCurlMgr;
use InstaApiWeb\EnumAction;
use InstaApiWeb\EnumEntity;
use business\Proxy;
use business\CookiesRequest;

class CurlMgr_test extends CI_Controller {

    public function __construct() {
    parent::__construct();

    require_once config_item('business-proxy-class');
    require_once config_item('business-cookies_request-class');
    require_once config_item('thirdparty-insta_curl_mgr-resource'); 
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
    $obj = new EnumEntity(EnumEntity::PERSON);
    var_dump($obj);
    echo "<h3>EnumProfile: ".$obj."</h3>";
    
    $obj = new EnumAction(EnumAction::CMD_FOLLOW);
    var_dump($obj);
    echo "<h3>EnumAction: ".$obj."</h3>";
    
    $obj = new InstaCurlMgr(new EnumEntity(EnumEntity::PERSON), new EnumAction(EnumAction::CMD_FOLLOW));
    var_dump($obj);
    echo $obj->make_curl(new Proxy(), new CookiesRequest("", "", "", ""));
    
    //$obj = new InstaProfileType(InstaProfileType::PERSON);
    //var_dump($obj);
    
    
  }

}
