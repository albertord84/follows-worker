<?php

namespace business {

  require_once config_item('business-user-class');
  require_once config_item('business-user-class');

  
  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an Client business class.
   * 
   */
  class Client extends User {

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $Plane_id;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Insta_id;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_number;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_status_id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_cvc;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_name;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_exp_month;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Credit_card_exp_year;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Pay_day;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Initial_order_key;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Order_key;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Pending_Order_key;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Actual_payment_data;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Insta_followers_ini;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Insta_following;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $HTTP_SERVER_VARS;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Foults;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Last_access;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Cookies;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Utm_source;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Unfollow;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Observation;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Unfollow_total;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Like_first;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Paused;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Ticket_peixe_urbano;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Ticket_peixe_urbano_status_id;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Purchase_counter;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Retry_payment_counter;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Purchase_access_token;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Ticket_access_token;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Retry_registration_counter;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Proxy;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Mundi_to_vindi;
    
    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Status_date;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Reference_profiles = array();

    /**
     * 
     * @todo Class constructor.
     * 
     */
    function __construct() {
      parent::__construct();
      
      $ci = &get_instance();
      $ci->load->model('clients_model');
      $ci->load->model('db_model');
      
      //$ci->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');
    }

        /**
     * Get client data
     * @param int $client_id
     * @return DataSet  
     */
    public function load_data(int $id) {
      parent::load_data($id);
      
      $ci = &get_instance();
      $data = $ci->clients_model->get_by_id($id);
      //$data = $ci->db_model->get_client_data($id);

      $this->fill_data($data);
    }
    
    private function fill_data(\stdClass $data) {
      $this->Plane_id = $data->plane_id;
      $this->Insta_id = $data->insta_id;
      $this->Credit_card_number = $data->credit_card_number;
      $this->Credit_card_status_id = $data->credit_card_status_id;
      $this->Credit_card_cvc = $data->credit_card_cvc;
      $this->Credit_card_name = $data->credit_card_name;
      $this->Credit_card_exp_month = $data->credit_card_exp_month;
      $this->Credit_card_exp_year = $data->credit_card_exp_year;
      $this->Pay_day = $data->pay_day;
      $this->Initial_order_key = $data->initial_order_key;
      $this->Order_key = $data->order_key;
      $this->Pending_Order_key = $data->pending_Order_key;
      $this->Actual_payment_data = $data->actual_payment_data;
      $this->Insta_followers_ini = $data->insta_followers_ini;
      $this->Insta_following = $data->insta_following;
      $this->HTTP_SERVER_VARS = $data->HTTP_SERVER_VARS;
      $this->Foults = $data->foults;
      $this->Last_access = $data->last_access;
      $this->Cookies = $data->cookies;
      $this->Utm_source = $data->utm_source;
      $this->Unfollow = $data->unfollow;
      $this->Observation = $data->observation;
      $this->Unfollow_total = $data->unfollow_total;
      $this->Like_first = $data->like_first;
      $this->Paused = $data->paused;
      $this->Ticket_peixe_urbano = $data->ticket_peixe_urbano;
      $this->Ticket_peixe_urbano_status_id = $data->ticket_peixe_urbano_status_id;
      $this->Purchase_counter = $data->purchase_counter;
      $this->Retry_payment_counter = $data->retry_payment_counter;
      $this->Purchase_access_token = $data->purchase_access_token;
      $this->Ticket_access_token = $data->ticket_access_token;
      $this->Retry_registration_counter = $data->retry_registration_counter;
      $this->Proxy = $data->proxy;
      $this->Mundi_to_vindi = $data->mundi_to_vindi;
      $this->Status_date = $data->status_date;
      $this->Reference_profiles = array();
    }
    
    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function set_client_cookies($client_id = NULL, $cookies = NULL) {
      $ci = &get_instance();
      $client_id = $client_id ? $client_id : $this->id;
      $cookies = $cookies ? $cookies : $this->cookies;
      $result = $ci->db_model->set_client_cookies($client_id, $cookies);
      return $result;
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function set_client_status($client_id = NULL, $status_id = NULL) {
      $ci = &get_instance();
      try {
        $client_id = $client_id ? $client_id : $this->id;
        $status_id = $status_id ? $status_id : $this->status_id;
        //$DB = new \follows\cls\DB();
        $result = $ci->db_model->set_client_status($client_id, $status_id);
        if ($result) {
          print "Client $client_id to status $status_id!!!";
        } else {
          print "FAIL CHANGING Client $client_id to status $status_id!!!";
        }
      } catch (Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }
    
    /**
     * 
     * @return type
     */
    public function get_clients() {
      $ci = &get_instance();
      return $ci->db_model->get_clients_data();
    }

    
    /**
     * Obtiene 
     * @param type $client_id
     */
    //public function get_reference_profiles($client_id) {
    public function get_reference_profiles() {
      $ci = &get_instance();
        //$client_id = $client_id ? $client_id : $this->id;
        //$ref_profs_data = $ci->db_model->get_reference_profiles_data($client_id);
        $rows = $ci->db_model->get_reference_profiles_data($this->Id);
        foreach ($rows as $item){
          array_push($this->reference_profiles, $Ref_Prof);
        }
        
        /*while ($prof_data = $ref_profs_data->fetch_object()) {
          //CONCERTAR quitar follows...
          $Ref_Prof = new \follows\cls\Reference_profile();
          //print_r($prof_data);
          // Update Ref Prof Data if not privated
          // TODO: Chechk if privated RP
//                    if ($Ref_Prof->is_private($prof_data->insta_name) === FALSE) {
          $Ref_Prof->id = $prof_data->id;
          $Ref_Prof->insta_id = $prof_data->insta_id;
          $Ref_Prof->insta_name = $prof_data->insta_name;
          $Ref_Prof->insta_follower_cursor = $prof_data->insta_follower_cursor;
          $Ref_Prof->deleted = ($prof_data->deleted || ($prof_data->deleted == "1")) ? true : false;
          $Ref_Prof->type = $prof_data->type;
          $Ref_Prof->end_date = $prof_data->end_date;
          $Ref_Prof->status = $prof_data->status_id;
          array_push($this->reference_profiles, $Ref_Prof);
        }*/

    }
    

    /**
     *
     * @param type $offset
     * @param type $rows
     * @return type
     */
    //DELETE FUNTION
    /* public function get_begginer_client($offset = 0, $rows = 0) {
      $client_data = $ci->db_model->get_biginner_data($offset, $rows);
      return $client_data;
      //$Client = $this->fill_client_data($client_data);
      //return $Client;
      } */
    
    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function insert_clients_daily_report() {
      $ci = &get_instance();
      try {
        $Clients = array();
        //$DB = new \follows\cls\DB();
        $clients_data = $ci->db_model->get_clients_data_for_report();
        while ($client_data = $clients_data->fetch_object()) {
          $profile_data = (new Reference_profile())->get_insta_ref_prof_data($client_data->login, $client_data->id);
          if ($profile_data) {
            $result = $ci->db_model->insert_client_daily_report($client_data->id, $profile_data);
            var_dump($client_data->login);
            var_dump("Cantidad de follows = " . $profile_data->follower_count);
            echo '<br><br><br>';
          } else {
            var_dump($client_data);
          }
          sleep(5); // secounds
        }
      } catch (Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function dumbu_statistics() {
      $ci = &get_instance();
      try {
        $Clients = array();
        //$DB = new \follows\cls\DB();
        $time = strtotime(date("Y-m-d H:00:00"));
        $datas = $ci->db_model->get_dumbu_statistics();
        $arr = '(';
        $cols = '(';
        foreach ($datas as $value) {
          switch ($value['status_id']) {
            case "1":
              $cols .= "ACTIVE,";
              break;
            case "2":
              $cols .= "BLOCKED_BY_PAYMENT,";
              break;
            case "3":
              $cols .= "BLOCKED_BY_INSTA,";
              break;
            case "4":
              $cols .= "DELETED,";
              break;
            case "5":
              $cols .= "INACTIVE,";
              break;
            case "6":
              $cols .= "PENDING,";
              break;
            case "7":
              $cols .= "UNFOLLOW,";
              break;
            case "8":
              $cols .= "BEGINNER,";
              break;
            case "9":
              $cols .= "VERIFY_ACCOUNT,";
              break;
            case "10":
              $cols .= "BLOCKED_BY_TIME,";
              break;
            case "21":
              $cols .= "PAYING_CUSTOMERS,";
              break;
          }
          $arr .= $value['cnt'] . ',';
        }

        $datas2 = $ci->db_model->get_dumbu_paying_customers();
        foreach ($datas2 as $value) {
          $cols .= "PAYING_CUSTOMERS,";
          $arr .= $value['cnt'] . ',';
        }

        $cols .= 'date)';
        $arr .= $time . ')';
        $ci->db_model->insert_dumbu_statistics($cols, $arr);
      } catch (Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function create_daily_work($client_id) {
      $ci = &get_instance();
      $Client = $this->get_client($client_id);
      if (count($Client->reference_profiles) > 0) {
        $DIALY_REQUESTS_BY_CLIENT = $Client->to_follow;
        $to_follow_unfollow = $DIALY_REQUESTS_BY_CLIENT / count($Client->reference_profiles);
//                $to_follow_unfollow = $GLOBALS['sistem_config']->DIALY_REQUESTS_BY_CLIENT / count($Client->reference_profiles);
        // If User status = UNFOLLOW he do 0 follows
        $to_follow = $Client->status_id != user_status::UNFOLLOW ? $to_follow_unfollow : 0;
        $to_unfollow = $to_follow_unfollow;
        foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
//$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
          $ci->db_model->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
        }
      } else {
        echo "Not reference profiles: $Client->login <br>\n<br>\n";
      }
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function rp_workable_count() {
      $ci = &get_instance();
      $count = 0;
      $Robot = new Robot();
      $proxy = $Robot->get_proxy_str($this);
      $status = new reference_profiles_status();
      //$DB = new DB();
      if ($this->reference_profiles) {
        foreach ($this->reference_profiles as $ref_prof) {
          if ($ref_prof->deleted && $ref_prof->status != $status->DELETED) {
            $ci->db_model->update_reference_profile_status($status->DELETED, $ref_prof->id);
          } else if ($ref_prof->end_date != NULL && $ref_prof->status != $status->ENDED) {
            $ci->db_model->update_reference_profile_status($status->ENDED, $ref_prof->id);
          } else if (!$Robot->exist_reference_profile($ref_prof->insta_name, $ref_prof->type, $ref_prof->insta_id, json_decode($this->cookies), $proxy) && $ref_prof->status != $status->LOCKED) {
            $ci->db_model->update_reference_profile_status($status->LOCKED, $ref_prof->id);
          } else if ($ref_prof->status == $status->ACTIVE) {
            $count++;
          }
        }
      }
      return $count;
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function delete_daily_work($client_id) {
      $ci = &get_instance();
      $ci->db_model->delete_daily_work_client($client_id);
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function sign_in($Client) {
      $ci = &get_instance();
      try {
        $login_data = $ci->InstaApiLib->login($this->login, $this->pass, $this->Proxy);
      } catch (Exception $exc) {
        //CONCERTAR myDB
        $myDB->insert_event_to_washdog($Client->id, $exc->getMessage(), $source);
        echo $exc->getTraceAsString();
      }


      if (is_object($login_data) && isset($login_data->json_response->authenticated) && $login_data->json_response->authenticated) {
        $this->set_client_cookies($Client->id, json_encode($login_data));
        echo "<br>\n Autenticated Client!!! Cookies changed: $Client->login ($Client->id) <br>\n\n\n<br>\n";
        return $login_data;
      } else {
        echo "<br>\n NOT Autenticated Client!!!: $Client->login ($Client->id) <br>\n";
        var_dump($login_data->json_response);
        echo "\n\n__________________________________________________________________<br>\n";
        // Chague client status
        if (isset($login_data->json_response) && $login_data->json_response->status == 'ok') {
          if ($login_data->json_response->message == 'checkpoint_required') {
            if ($Client->status_id != user_status::VERIFY_ACCOUNT) {
              $this->set_client_status($Client->id, user_status::VERIFY_ACCOUNT);
            }
          } else
          if ($login_data->json_response->message == 'incorrect_password') {
            if ($Client->status_id != user_status::BLOCKED_BY_INSTA) {
              $this->set_client_status($Client->id, user_status::BLOCKED_BY_INSTA);
            }
          } else
          if ($login_data->json_response->message == 'problem_with_your_request') {
            $ci->db_model->insert_event_to_washdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, "Unknow error with your request");
          } else {
            $ci->db_model->insert_event_to_washdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, $login_data->json_response->message);
          }
        }
        $this->set_client_cookies($Client->id, NULL);
        return NULL;
      }
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function check_insta_user() {
      
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function checkpoint_requested(string $login, string $pass, \InstaApiWeb\VerificationChoice $choise = \InstaApiWeb\VerificationChoice::Email) {
      $login_data = json_decode($this->cookies);
      //$proxy = $this->GetProxy();
      $client = new \InstaApiWeb\InstaClient($this->insta_id, $login_data, $this->Proxy);
      $res = $client->checkpoint_requested($login, $pass, $choise);
      $this->cookies = json_encode($client->cookies);
      //guardar las cookies en la Base de Datos
      return $res;
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function make_checkpoint(string $login, string $code) {
      //las cookies son las actualizadas de la BD
      $login_data = json_decode($this->cookies);
      //$proxy = $this->GetProxy();
      $client = new \InstaApiWeb\InstaClient($this->insta_id, $login_data, $this->Proxy);
      $res = $client->make_checkpoint($login, $code);
      $this->cookies = json_encode($client->cookies);
      //guardar las cookies en la Base de Datos
      return $res;
    }

  }

}
?>
