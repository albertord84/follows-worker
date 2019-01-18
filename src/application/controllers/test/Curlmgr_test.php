<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use InstaApiWeb\InstaCurlMgr;
use InstaApiWeb\InstaActionType;
use InstaApiWeb\InstaProfileType;

class CurlMgr_test extends CI_Controller {

    public function __construct() {
    parent::__construct();

    require_once config_item('thirdparty-insta_curl_mgr-resource');    
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

  public function run() {
    $obj = new InstaCurlMgr(InstaProfileType::PERSON, InstaActionType::LIKE);
    var_dump($obj);
  }

}
