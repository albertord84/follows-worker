<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

use business\Proxy;
use business\Admin;
use business\Attendent;
use business\SystemConfig;
use business\Client;
use business\ProxyManager;
use business\StatusProfiles;

class Business extends CI_Controller {

  public function __construct() {
    parent::__construct();

    require_once config_item('business-proxy-class');
    require_once config_item('business-admin-class');
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
    echo "<h1>Test Client Business</h1>";
    $obj = new Client();
    echo "[new] Client_business ==> (<b>ok</b>)<br>";

    $array = $obj->get_clients();
    echo "[get] get_clients() => result: " . count($array) . " ==> (<b>ok</b>)<br>"; //var_dump($array)

    $array = $obj->get_client(1);
    echo "[get] get_client() => result: " . count($array) . " ==> (<b>ok</b>)<br>"; //var_dump($array)
    
    //$array = $obj->get_begginer_client(0, 5);
    //echo "[get] get_begginer_client() => result: ".count($array)."<br>"; var_dump($array);
    //
    //$array = $obj->get_reference_profiles();
    //echo "[insert] get_reference_profiles() => result: ".count($array)."<br>";
    //
    //echo "[fill] () => result: ".count($array)."<br>";
    //echo "[get] () => result: ".count($array)."<br>";
    
    //======= PROXY =======//
    echo "<h1>Test Proxy Business</h1>";
    $obj = new Proxy();
    echo "[new] Proxy_business ==> (<b>ok</b>)<br>";

    $obj->load();
    echo "[load] load() ==> (<b>ok</b>)";

    //======= PROXY-MANAGER =======//
    echo "<h1>Test ProxyManager Business</h1>";
    $obj = new ProxyManager();
    echo "[new] ProxyManager_business ==> (<b>ok</b>)<br>";

    $obj->UpdateUserProxy();
    echo "[update] UpdateUserProxy ==> (<b>ok</b>)<br>";

    $obj->GetNextProxy();
    echo "[get] GetNextProxy ==> (<b>ok</b>)<br>";

    $obj->GetReservedProxy();
    echo "[get] GetReservedProxy ==> (<b>ok</b>)";

    //======= STATUS-PROFILE =======//
    echo "<h1>Test StatusProfiles Business</h1>";
    $obj = new StatusProfiles();
    echo "[new] StatusProfiles_business ==> (<b>ok</b>)";

    //======= ADMIN =======//
    echo "<h1>Test Admin Business</h1>";
    $obj = new Admin();
    echo "[new] Admin_business ==> (<b>ok</b>)";

    //======= ATTENDENT =======//
    echo "<h1>Test Attendent Business</h1>";
    $obj = new Attendent();
    echo "[new] Attendent_business ==> (<b>ok</b>)";

    //======= SYSTEM-CONFIG =======//
    echo "<h1>Test SystemConfig Business</h1>";
    $obj = new SystemConfig();
    echo "[new] SystemConfig_business ==> (<b>ok</b>)";
  }

}
