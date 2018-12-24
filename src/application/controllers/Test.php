<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

//using namespace follows\cls
//use follows\cls;

/* $file = getcwd()."/application/models/DB.php";
  require_once $file; */ //---> TEST #1-faul!!!

class Test extends CI_Controller {

  public function __construct() {
    parent::__construct();
    //echo getcwd()."/application/models/DB.php";
    //require_once config_item('business-client-class'); 
    //require_once getcwd()."/application/models/DB.php";
    //$file = getcwd()."/application/models/DB.php";
    //if(file_exists($file)) echo "el fichero existe";
    //require_once $file;
    //include_once $file; //---> TEST #2-faul!!!
    
    require_once config_item('db-exception-class');
  }

  public function index() {
    
  }

  public function test_exception() {
    echo "test_exception inside<br>";
    //$this->load->library('MY_Exceptions');
    try {
      //throw new MY_Exceptions('a message for my exception!!!');
      //throw new MY_Exceptions();
      //throw new Exception();
      //throw new CI_Exception();

      throw new DB_Exception("mi mensaje para la exception");
    } 
    //catch (MY_Exceptions $e) {
    //catch (Exception $e) {
    catch (DB_Exception $e){
      //echo $e->getMessage();
      //$e->show_miError();
      echo "Estoy en el catch<br><br>";
      $e->funciónPersonalizada();
      
      //var_dump($e);
    }
    /*catch (CI_Exception $e)
    {
      echo "Capture la CI_Exception<br><br>";
    }*/
  }

  public function db() {
    echo "<br><br>ok";

    //if(file_exists("../models/DB.php")) echo "el fichero existe";
    require_once getcwd() . "/application/models/DB.php";
    //require_once "../mophpdels/DB.php";

    $obj = new follows\cls\DB();
    //$obj = new DB(); //---> TEST #3-faul!!!
  }

  public function db_model() {
    $this->load->model('db_model');
    //$this->db_model->myFunc();

    try {
      $items = $this->db_model->get_clients_by_status(1);
      print_r($items);
    } 
    //catch (Exception $e) {
    //catch(MY_Exceptions $e){
    //catch(\Exception $e){
    //catch(\ErrorException $e){
    //catch(\Error $e){
    catch (DB_Exception $e){ 
      echo "<h2>try-catch del controller</h2>";
      //echo $e->getDB_error();
      echo $e->getErrorInfo();
      //echo "capture la exception en el db_model_controller<br><br>";
      //print_r($this->db->error()); echo "<br>";
      //echo $this->db->error()
      
      //print_r($e);
      //log_message('error', $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
      // on error 
    }
  }

  public function entity($param, $action, $id) {
    echo "<b>Param: </b>" . $param . "<br><b>Action: </b>" . $action . "<br><br>";

    switch ($param) {
      case 'black_and_white_list': $this->e_Black_and_white_list($action, $id);
        break;
      case 'client_payment': $this->e_Client_payment($action, $id);
        break;
      case 'clients': $this->e_Clients($action, $id);
        break;
      case 'credit_card_status': $this->e_Credit_card_status($action, $id);
        break;
      case 'daily_report': $this->e_Daily_report($action, $id);
        break;
      case 'daily_work': $this->e_Daily_work($action, $id);
        break;
      case 'dumbu_statistic': $this->e_Dumbu_statistic($action, $id);
        break;



      case 'proxy': $this->e_Proxy($action, $id);
        break;

      /*



        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break;
        case '': $this->($action, $id); break; */

      default: echo "<h2>param wrong!!!</h2>";
    }
  }

  private function e_Proxy($action, $id) {

    $this->load->model('proxy_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->proxy_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->proxy_model->get_all();
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Black_and_white_list($action, $id) {
    $this->load->model('black_and_white_list_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->black_and_white_list_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->black_and_white_list_model->get_all();
      print_r($items);
    } else {
      echo "<h2>action wrong!!!</h2>";
    }
  }

  private function e_Client_payment($action, $id) {
    $this->load->model('client_payment_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->client_payment_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->client_payment_model->get_all();
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Clients($action, $id) {

    $this->load->model('clients_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->clients_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->clients_model->get_all(10, 5);
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Credit_card_status($action, $id) {
    $this->load->model('credit_card_status_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->credit_card_status_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->credit_card_status_model->get_all();
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Daily_report($action, $id) {
    $this->load->model('daily_report_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->daily_report_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->daily_report_model->get_all(4);
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Daily_work($action, $id) {

    $this->load->model('daily_work_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->daily_work_model->get_by_id($id);
      print_r($items);
    } else if ($action == 'get-all') {
      $items = $this->daily_work_model->get_all();
      print_r($items);
    } else {
      echo "action wrong!!!";
    }
  }

  private function e_Dumbu_statistic($action, $id) {

    $this->load->model('dumbu_statistic_model');
    //$this->load->modelD'proxy_model', 'proxy');

    if ($action == 'get-by-id') {
      $items = $this->dumbu_statistic_model->get_by_id($id);
      var_dump($items);
    } else if ($action == 'get-all') {
      $items = $this->dumbu_statistic_model->get_all();
      var_dump($items);
    } else {
      echo "action wrong!!!";
    }
  }

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */

  /* private function e_?($action, $id)
    {

    $this->load->model('?_model');
    //$this->load->model('proxy_model', 'proxy');

    if ($action == 'get-by-id')
    {
    $items = $this->?_model->get_by_id($id);
    var_dump($items);
    }
    else if ($action == 'get-all'){
    $items = $this->?_model->get_all();
    var_dump($items);
    }
    else {
    echo "action wrong!!!";
    }
    } */
}
