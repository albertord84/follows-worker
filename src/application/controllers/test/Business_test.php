<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use business\Proxy;
//use business\Admin;
use business\Attendent;
use business\SystemConfig;
use business\Client;
use business\ProxyManager;
use business\StatusProfiles;

class Business_test extends CI_Controller {

  public function __construct() {
    parent::__construct();

    require_once config_item('business-proxy-class');
    //require_once config_item('business-admin-class');
    require_once config_item('business-attendent-class');
    require_once config_item('business-system_config-class');
    require_once config_item('business-client-class');
    require_once config_item('business-proxy_manager-class');
    require_once config_item('business-status_profiles-class');
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

  public function run() {
    //======= CLIENT =======//
    echo "<pre>";
    echo "<h2>Test Client Business</h2>";
    $obj = new Client();
    echo "[new] Client_business ==> (<b>ok</b>)<br>";

    $array = $obj->get_clients(); 
    echo "[get] get_clients() => result: " . count($array) . " ==> (<b>ok</b>)<br>"; //var_dump($array)

    $array = $obj->load_data(1); var_dump($obj); echo "<h1>$obj->Id</h1>";
    echo "[get] load_from_db() => result: " . count($array) . " ==> (<b>ok</b>)<br>"; //var_dump($array);
     
    //$array = $obj->get_reference_profiles();
    //echo "[insert] get_reference_profiles() => result: ".count($array)."<br>";
    //
    //echo "[fill] () => result: ".count($array)."<br>";
    //echo "[get] () => result: ".count($array)."<br>";
    
    //======= PROXY =======//
    echo "<h2>Test Proxy Business</h2>";
    $obj = new Proxy();
    echo "[new] Proxy_business ==> (<b>ok</b>)<br>";

    $obj->load_from_db(1);
    echo "[load] load() ==> (<b>ok</b>)"; //var_dump($obj);

    //======= PROXY-MANAGER =======//
    echo "<h2>Test ProxyManager Business</h2>";
    $obj = new ProxyManager();
    echo "[new] ProxyManager_business ==> (<b>ok</b>)<br>";

    $obj->UpdateUserProxy();
    echo "[update] UpdateUserProxy ==> (<b>ok</b>)<br>";

    $obj->GetNextProxy();
    echo "[get] GetNextProxy ==> (<b>ok</b>)<br>";

    $obj->GetReservedProxy();
    echo "[get] GetReservedProxy ==> (<b>ok</b>)";

    //======= STATUS-PROFILE =======//
    echo "<h2>Test StatusProfiles Business</h2>";
    $obj = new StatusProfiles();
    echo "[new] StatusProfiles_business ==> (<b>ok</b>)";

    //======= ADMIN =======//
    /*echo "<h2>Test Admin Business</h2>";
    $obj = new Admin();
    echo "[new] Admin_business ==> (<b>ok</b>)";*/

    //======= ATTENDENT =======//
    echo "<h2>Test Attendent Business</h2>";
    $obj = new Attendent();
    echo "[new] Attendent_business ==> (<b>ok</b>)";

    //======= SYSTEM-CONFIG =======//
    echo "<h2>Test SystemConfig Business</h2>";
    $obj = new SystemConfig();
    echo "[new] SystemConfig_business ==> (<b>ok</b>)";
    echo "</pre>";
    
    echo "<h2>"; print_r(memory_get_usage()); echo '<br>'; echo "</h2>";
  }

}
