<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use InstaApiWeb\InstaCurlMgr;
use InstaApiWeb\EnumAction;
use InstaApiWeb\EnumProfile;
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
  }

  public function run() {
    $obj = new EnumProfile(EnumProfile::PERSON);
    var_dump($obj);
    echo "<h3>EnumProfile: ".$obj."</h3>";
    
    $obj = new EnumAction(EnumAction::FOLLOW);
    var_dump($obj);
    echo "<h3>EnumAction: ".$obj."</h3>";
    
    $obj = new InstaCurlMgr(new EnumProfile(EnumProfile::PERSON), new EnumAction(EnumAction::FOLLOW));
    var_dump($obj);
    echo $obj->make_curl_str(new Proxy(), new CookiesRequest("", "", "", ""));
    
    //$obj = new InstaProfileType(InstaProfileType::PERSON);
    //var_dump($obj);
    
    
  }

}
