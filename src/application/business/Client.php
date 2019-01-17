<?php

namespace business {

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
    public $credit_card_number;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $credit_card_status_id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $credit_card_cvc;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $credit_card_name;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $pay_day;

    /**
     * 
     * @access public
     * @var type
     *  
     */
    public $plane_id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $insta_id;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $status_date;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $insta_followers_ini;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $insta_following;

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
    public $cookies;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $to_follow;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $reference_profiles = array();

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $paused;

    /**
     * 
     * @access public
     * @var type 
     * 
     */
    public $Proxy;

    /**
     * 
     * @todo Class constructor.
     * 
     */
    function __construct() {
      parent::__construct();

      $this->CI->load->model('db_model');
      $this->CI->load->library("APIInstaWeb/InstaApi_lib", null, 'InstaApi_lib');
    }

    /**
     * 
     * @return type
     */
    public function get_clients() {
      return $this->CI->db_model->get_clients_data();
    }

    /**
     * Get client data
     * @param int $client_id
     * @return DataSet  
     */
    public function load_from_db(int $id) {
      $client_data = $this->CI->db_model->get_client_data($id);
      $this->fill_client_data($client_data);
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    private function fill_client_data($client_data) {

      /*
        $credit_card_number;
        $credit_card_status_id;
        $credit_card_cvc;
        $credit_card_name;
        $pay_day;
        $plane_id;
        $insta_id;
        $status_date;
        $insta_followers_ini;
        $insta_following;
        $HTTP_SERVER_VARS;
        $cookies;
        $to_follow;
        $reference_profiles = array();
        $paused;
        $Proxy;
       */

      $this->id = $client_data->user_id;
      $this->name = $client_data->name;
      $this->login = $client_data->login;
      $this->pass = $client_data->pass;
      $this->email = $client_data->email;
      $this->insta_id = $client_data->insta_id;
      $this->plane_id = $client_data->plane_id;
      $this->to_follow = isset($client_data->to_follow) ? $client_data->to_follow : 0;
      $this->status_id = $client_data->status_id;
      $this->insta_following = $client_data->insta_following;
      $this->cookies = $client_data->cookies;
      $this->paused = $client_data->paused;
      $this->HTTP_SERVER_VARS = $client_data->HTTP_SERVER_VARS;
      $this->init_date = $client_data->init_date;
      $this->last_access = $client_data->last_access;
      //$this->get_reference_profiles($this->id);
      //$this->Proxy = new Proxy();
      //$this->Proxy->load($client_data->proxy_id);
    }

    /**
     *
     * @param type $offset
     * @param type $rows
     * @return type
     */
   //DELETE FUNTION
   /* public function get_begginer_client($offset = 0, $rows = 0) {
      $client_data = $this->CI->db_model->get_biginner_data($offset, $rows);
      return $client_data;
      //$Client = $this->fill_client_data($client_data);
      //return $Client;
    }*/  

    /**
     * Obtiene 
     * @param type $client_id
     */
    public function get_reference_profiles($client_id = NULL) {
      try {
        $client_id = $client_id ? $client_id : $this->id;
        $ref_profs_data = $this->CI->db_model->get_reference_profiles_data($client_id);
        while ($prof_data = $ref_profs_data->fetch_object()) {
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
//                    }
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
    public function insert_clients_daily_report() {
      try {
        $Clients = array();
        //$DB = new \follows\cls\DB();
        $clients_data = $this->CI->db_model->get_clients_data_for_report();
        while ($client_data = $clients_data->fetch_object()) {
          $profile_data = (new Reference_profile())->get_insta_ref_prof_data($client_data->login, $client_data->id);
          if ($profile_data) {
            $result = $this->CI->db_model->insert_client_daily_report($client_data->id, $profile_data);
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
      try {
        $Clients = array();
        //$DB = new \follows\cls\DB();
        $time = strtotime(date("Y-m-d H:00:00"));
        $datas = $this->CI->db_model->get_dumbu_statistics();
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

        $datas2 = $this->CI->db_model->get_dumbu_paying_customers();
        foreach ($datas2 as $value) {
          $cols .= "PAYING_CUSTOMERS,";
          $arr .= $value['cnt'] . ',';
        }

        $cols .= 'date)';
        $arr .= $time . ')';
        $this->CI->db_model->insert_dumbu_statistics($cols, $arr);
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
      //$DB = new DB();
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
          $this->CI->db_model->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
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
      $count = 0;
      $Robot = new Robot();
      $proxy = $Robot->get_proxy_str($this);
      $status = new reference_profiles_status();
      //$DB = new DB();
      if ($this->reference_profiles) {
        foreach ($this->reference_profiles as $ref_prof) {
          if ($ref_prof->deleted && $ref_prof->status != $status->DELETED) {
            $this->CI->db_model->UpdateReferenceProfileStatus($status->DELETED, $ref_prof->id);
          } else if ($ref_prof->end_date != NULL && $ref_prof->status != $status->ENDED) {
            $this->CI->db_model->UpdateReferenceProfileStatus($status->ENDED, $ref_prof->id);
          } else if (!$Robot->exist_reference_profile($ref_prof->insta_name, $ref_prof->type, $ref_prof->insta_id, json_decode($this->cookies), $proxy) && $ref_prof->status != $status->LOCKED) {
            $this->CI->db_model->UpdateReferenceProfileStatus($status->LOCKED, $ref_prof->id);
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
      //$DB = new DB();
      $this->CI->db_model->delete_daily_work_client($client_id);
    }

    /**
     * 
     * @todo
     * @param type
     * @return
     * 
     */
    public function sign_in($Client) {
      //$DB = new DB();

      try {
        $login_data = $this->CI->InstaApiLib->login($this->login, $this->pass, $this->Proxy);
      } catch (Exception $exc) {
        //CONCERTAR myDB
        $myDB->InsertEventToWashdog($Client->id, $exc->getMessage(), $source);
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
            $this->CI->db_model->InsertEventToWashdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, "Unknow error with your request");
          } else {
            $this->CI->db_model->InsertEventToWashdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, $login_data->json_response->message);
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
    public function set_client_cookies($client_id = NULL, $cookies = NULL) {
      try {
        $client_id = $client_id ? $client_id : $this->id;
        $cookies = $cookies ? $cookies : $this->cookies;
        //$DB = new \follows\cls\DB();
        $result = $this->CI->db_model->set_client_cookies($client_id, $cookies);
        /* if ($result) {
          //print "Client $client_id cookies changed!!!";
          } else {
          print "FAIL CHANGING Client $client_id cookies!!!";
          } */
        return $result;
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
    public function set_client_status($client_id = NULL, $status_id = NULL) {
      try {
        $client_id = $client_id ? $client_id : $this->id;
        $status_id = $status_id ? $status_id : $this->status_id;
        //$DB = new \follows\cls\DB();
        $result = $this->CI->db_model->set_client_status($client_id, $status_id);
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
     * @todo
     * @param type
     * @return
     * 
     */
    public function checkpoint_requested(string $login, string $pass, \ApiInstaWeb\VerificationChoice $choise = \ApiInstaWeb\VerificationChoice::Email) {
      $login_data = json_decode($this->cookies);
      $proxy = $this->GetProxy();
      $client = new \ApiInstaWeb\InstaClient($this->insta_id, $login_data, $proxy);
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
      $client = new \ApiInstaWeb\InstaClient($this->insta_id, $login_data, $this->Proxy);
      $res = $client->make_checkpoint($login, $code);
      $this->cookies = json_encode($client->cookies);
      //guardar las cookies en la Base de Datos
      return $res;
    }

  }

}
?>
