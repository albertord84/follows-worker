<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

//using namespace follows\cls
//use follows\cls;

/*$file = getcwd()."/application/models/DB.php";
require_once $file;*/ //---> TEST #1-faul!!!

class Test extends CI_Controller {
    public function __construct() {
      parent::__construct() ;
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
    
    public function library ()
    {
      //$params = array('username'  => 'isela');
       
      // -OK-
      //$this->load->library("APIInstaWeb/GeoProfile_lib", null, 'GeoProfile_lib');
      //$this->GeoProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/HashProfile_lib", null, 'HashProfile_lib');
      //$this->HashProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/InstaApi_lib", $params, 'InstaApi_lib');
      //$this->load->library("APIInstaWeb/InstaApi_lib", null, 'InstaApi_lib');
      //$this->InstaApi_lib->Msg();
      
      // SUPER ERRORSAZO!!!
      $this->load->library("APIInstaWeb/InstaClient_lib", null, 'InstaClient_lib');
      $this->InstaClient_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/PersonProfile_lib", null, 'PersonProfile_lib');
      //$this->PersonProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/InstaProfileList_lib", null, 'InstaProfileList_lib');
      //$this->InstaProfileList_lib->Msg();
      
      // -medio OK.. revisar constructor-
      //$this->load->library("APIInstaWeb/InstaProfile_lib", null, 'InstaProfile_lib');
      //$this->InstaProfile_lib->Msg();
      
      // -OK-
      //$this->load->library("APIInstaWeb/Proxy_lib", null, 'Proxy_lib');
      //$this->Proxy_lib->Msg();
      
      //$this->load->library("APIInstaWeb/Media_lib", null, 'Media_lib');
      //$this->Media_lib->Msg();
   
    }
    
    public function db ()
    {
      echo "<br><br>ok";
      
      //if(file_exists("../models/DB.php")) echo "el fichero existe";
      require_once getcwd()."/application/models/DB.php";
      //require_once "../mophpdels/DB.php";
      
      $obj = new follows\cls\DB(); 
      //$obj = new DB(); //---> TEST #3-faul!!!
    }
    
    public function db_model ()
    {
      $this->load->model('db_model');
      //$this->db_model->myFunc();

      try {
        $items = $this->db_model->get_clients_by_status(1);
        print_r($items);
      } 
      catch (Error $e){ 
        echo "<h2>Capture el error php</h2>";
        echo $e->getMessage();
      }
      catch (Db_Exception $e){ 
        echo "<h2>try-catch del controller</h2>";
        echo $e->getErrorInfo();
      }
    }
    
    public function entity ($param, $action, $id)
    {
      echo "<b>Param: </b>".$param."<br><b>Action: </b>".$action."<br><br>";
      
      switch ($param)
      {
        case 'black_and_white_list': $this->e_Black_and_white_list($action, $id); break;      
        case 'client_payment': $this->e_Client_payment($action, $id); break;
        case 'clients': $this->e_Clients($action, $id); break;
        case 'credit_card_status': $this->e_Credit_card_status($action, $id); break;
        case 'daily_report': $this->e_Daily_report($action, $id); break;
        case 'daily_work': $this->e_Daily_work($action, $id); break;
        case 'dumbu_statistic': $this->e_Dumbu_statistic($action, $id); break;     
        case 'dumbu_system_config': $this->e_Dumbu_system_config($action, $id); break;
        case 'faq': $this->e_Faq($action, $id); break;
        case 'payment_cause': $this->e_Payment_cause($action, $id); break;
        case 'payment_form': $this->e_Payment_form($action, $id); break;
        case 'payment_gateway': $this->e_Payment_gateway($action, $id); break;
        //case 'payment_gateway_values': $this->($action, $id); break;
        //case 'payments': $this->($action, $id); break;
        //case 'pendences': $this->($action, $id); break;
        //case 'plane': $this->($action, $id); break;
        case 'proxy': $this->e_Proxy($action, $id); break;
        //case 'ranking': $this->($action, $id); break;
        //case 'reference_profile': $this->($action, $id); break;
        //case 'reference_profiles_status': $this->($action, $id); break;
        //case 'robot': $this->($action, $id); break;
        //case 'salva_users': $this->($action, $id); break;
        //case 'ticket_bank': $this->($action, $id); break;
        //case 'ticket_peixe_urbano_status': $this->($action, $id); break;
        //case 'translation': $this->($action, $id); break;
        //case 'user_role': $this->($action, $id); break;
        //case 'user_status': $this->($action, $id); break;
        //case 'users': $this->($action, $id); break;
        //case 'washdog1': $this->($action, $id); break;
        //case 'washdog': $this->($action, $id); break;
        //case 'washdog_type': $this->($action, $id); break;
        //case 'worker': $this->($action, $id); break;
        //case 'worker_robot': $this->($action, $id); break;      
        default: echo "<h2>param wrong!!!</h2>";
      }     
    }
    
    private function e_Black_and_white_list($action, $id)
    {
      $this->load->model('black_and_white_list_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->black_and_white_list_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->black_and_white_list_model->get_all();
        print_r($items);
      }
      else {
        echo "<h2>action wrong!!!</h2>"; 
      }
    }
    
    private function e_Client_payment($action, $id)
    {
      $this->load->model('client_payment_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->client_payment_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->client_payment_model->get_all();
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Clients($action, $id)
    {
      
      $this->load->model('clients_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->clients_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->clients_model->get_all(10,5);
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Credit_card_status($action, $id)
    { 
      $this->load->model('credit_card_status_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->credit_card_status_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->credit_card_status_model->get_all();
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Daily_report($action, $id)
    {
      $this->load->model('daily_report_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->daily_report_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->daily_report_model->get_all(4);
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Daily_work($action, $id)
    {
      
      $this->load->model('daily_work_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->daily_work_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->daily_work_model->get_all();
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Dumbu_statistic($action, $id)
    {
      
      $this->load->model('dumbu_statistic_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->dumbu_statistic_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->dumbu_statistic_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Dumbu_system_config($action, $id)
    {
      
      $this->load->model('Dumbu_system_config_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->Dumbu_system_config_model->get_by_id($id);
        //$items = $this->Dumbu_system_config_model->get_by_name('MIN_NEXT_ATTEND_TIME');
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->Dumbu_system_config_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Faq($action, $id)
    {
      
      $this->load->model('Faq_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->Faq_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->Faq_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Payment_cause($action, $id)
    {
      
      $this->load->model('Payment_cause_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->Payment_cause_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->Payment_cause_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Payment_form($action, $id)
    {
      
      $this->load->model('Payment_form_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->Payment_form_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->Payment_form_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    private function e_Payment_gateway($action, $id)
    {
      
      $this->load->model('Payment_gateway_model');
      //$this->load->model('?_model', '?');
      
      if ($action == 'get-by-id')
      {
        $items = $this->Payment_gateway_model->get_by_id($id);
        var_dump($items);
      }
      else if ($action == 'get-all'){
        $items = $this->Payment_gateway_model->get_all();
        var_dump($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    /*private function e_Payment_gateway_values($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Payments($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Plane($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    private function e_Proxy($action, $id)
    {
      
      $this->load->model('proxy_model');
      //$this->load->model('proxy_model', 'proxy');
      
      if ($action == 'get-by-id')
      {
        $items = $this->proxy_model->get_by_id($id);
        print_r($items);
      }
      else if ($action == 'get-all'){
        $items = $this->proxy_model->get_all();
        print_r($items);
      }
      else {
        echo "action wrong!!!"; 
      }
    }
    
    /*private function e_Ranking($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Reference_profile($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Reference_profiles_status($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Robot($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Salva_users($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Ticket_bank($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Ticket_peixe_urbano_status($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Translation($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_User_role($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_User_status($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Users($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Washdog1($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Washdog($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Washdog_type($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Worker($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
    
    /*private function e_Worker_robot($action, $id)
    {
      
      $this->load->model('?_model');
      //$this->load->model('?_model', '?');
      
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
    }*/
}


