<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

  use business\Client;
  use business\SystemConfig;
  use business\worker\DailyWork;
  use business\worker\Robot;
  use business\worker\Worker;

class Worker_test extends CI_Controller {

    public function __construct() {
    parent::__construct();

    require_once config_item('business-client-class');
    require_once config_item('business-system_config-class');
    require_once config_item('business-daily_work-class');
    require_once config_item('business-robot-class');
    require_once config_item('business-worker-class');
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

  public function run() {
    //======= DAILY-WORK =======//
    echo "<pre>";
    echo "<h2>Test DailyWork Business</h2>";
    $obj = new DailyWork();
    echo "[new] DailyWork_business ==> (<b>ok</b>)<br>"; 
 
    //======= ROBOT =======//
    echo "<h2>Test Robot Business</h2>";
    $obj = new Robot();
    echo "[new] Robot_business ==> (<b>ok</b>)<br>";
       
    //======= WORKER =======//
    echo "<h2>Test Worker Business</h2>";
    $obj = new Worker();
    echo "[new] Worker_business ==> (<b>ok</b>)<br>";
    echo "</pre>";
  }

}
