<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
    public function __construct() {
      parent::__construct() ;
      //require_once config_item('business-client-class');  
    }
    
    public function index() {
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
        
        
        
        case 'proxy': $this->e_Proxy($action, $id); break;
        
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
        case '': $this->($action, $id); break;*/
      
        default: echo "<h2>param wrong!!!</h2>";
      }     
    }
    
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
    
    private function e_Black_and_white_list($action, $id)
    {
      $this->load->model('black_and_white_list_model');
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->model('proxy_model', 'proxy');
      
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
      //$this->load->modelD'proxy_model', 'proxy');
      
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
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
    
    /*private function e_?($action, $id)
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
    }*/
}


