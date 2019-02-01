<?php

namespace business\worker {

  use business\Business;

  require_once config_item('business-class');

//require_once config_item('business-robot-class');

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an Worker worker class.
   * 
   */
  class Worker extends Business {

    //put your code here
    /*
     * +id
      +IP
      +robots
      +config
      +work_queue = array()
      +dir
      +Robot
      +Gmail
     * ------------------------------------------
      +__construct($DB = NULL, $id = -1)

      >>>>>> +get_worker_config() <<<<<<<<                      //EN ROJO EN EL MODELO
      >>>>>> +request_follow_unfollow_work() <<<<<<<            //EN ROJO EN EL MODELO
      >>>>>> +send_check_insta_user_work($Client) <<<<<<        //EN ROJO EN EL MODELO
      >>>>>> +have_work() <<<<<<<<                              //EN ROJO EN EL MODELO
      >>>>>> +get_work() : DailyWork <<<<<<<                    //EN ROJO EN EL MODELO

     */

    public $id;
    private $config;
    public $work_queue = array();
    public $dir;
    public $robot;
    public $mail;

    /* public function __construct($id = -1) {
      $this->Robot = new Robot($DB); //CONCERTAR
      $this->Robot->config = $GLOBALS['sistem_config'];
      $this->Robot->id = $id;
      $this->id = $id;
      $this->Gmail = new Gmail();
      //$this->DB = $DB ? $DB : new \follows\cls\DB();
      $this->load->model('db_model');
      } */

    function __construct() {
      $ci = &get_instance();

      $ci->load->model('db_model');
      //$ci->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');

    }

    // LISTA!!!
    public function get_worker_config() {
      return $config;
    }

    // LISTA!!!
    public function prepare_daily_work(bool $not_mail = false) {
      $ci = &get_instance();
      // Get Users Info
      $Clients = array();
      if ($client_id == NULL) {
        $Clients = (new Client())->get_clients();
      } else {
        array_push($Clients, (new Client())->get_client($client_id));
      }
//          
//          $Client = (new Client())->get_client(19546);  Testar, cliente JA

      $Client = new Client();
      foreach ($Clients as $Client) { // for each CLient
        /* if($Client->id == 1)
          { */
        if (!$Client->cookies) {
          // Log user with curl in istagram to get needed session data
          $login_data = $Client->sign_in($Client);
          if ($login_data !== NULL) {
            $Client->cookies = json_encode($login_data);
          }
        }
        if ($Client->cookies && !$Client->paused) {
          //var_dump($Client->login);
          $cookies = json_decode($Client->cookies);
          if (isset($cookies->csrftoken) && $cookies->csrftoken != NULL && $cookies->csrftoken != "" &&
                  isset($cookies->ds_user_id) && $cookies->ds_user_id != NULL && $cookies->ds_user_id != "" &&
                  isset($cookies->sessionid) && $cookies->sessionid != NULL && $cookies->sessionid != "" &&
                  isset($cookies->mid) && $cookies->mid != NULL && $cookies->mid != "") {//enejkefnjknl o
            //Jose R: si tiene los 4 parametros de las cookies, devemos intentar hacer una operacion (coger 10 seguidores de qq RP)
            //para chekear que esas cookies estan correctas, si no, bloquear por ssenha errada  status_id=3
            $ci->db_model->cmd_create_followed($Client->id);
            print("<br>\nAutenticated Client: $Client->login <br>\n<br>\n");
            $Client->set_client_status($Client->id, user_status::ACTIVE);
            // Distribute work between clients
            $RPWC = $Client->rp_workable_count();
            print("<br>\nWorkable Referenc Profile: $RPWC <br>\n<br>\n");
            if (strtotime("today") - $Client->init_date < 40 * 24 * 60 * 60) {
              $DIALY_REQUESTS_BY_CLIENT = 480;
            } else {
              $DIALY_REQUESTS_BY_CLIENT = $Client->to_follow;
            }
            if ($RPWC > 0) {
              $to_follow_unfollow = $DIALY_REQUESTS_BY_CLIENT / $RPWC;
              //$to_follow_unfollow = $GLOBALS['sistem_config']->DIALY_REQUESTS_BY_CLIENT / $RPWC;
              // If User status = UNFOLLOW he do 0 follows
              $to_follow = $Client->status_id != user_status::DUMBU_UNFOLLOW ? $to_follow_unfollow : 0;
              $to_unfollow = $to_follow_unfollow;
              foreach ($Client->reference_profiles as $Ref_Prof) { // For each reference profile
                //$Ref_prof_data = $this->Robot->get_insta_ref_prof_data($Ref_Prof->insta_name);
                if (!$Ref_Prof->deleted && $Ref_Prof->end_date == NULL) {
                  $valid_geo = ($Ref_Prof->type == 1 && ($Client->plane_id == 1 || $Client->plane_id > 3));
                  $valid_hastag = ($Ref_Prof->type == 2 && ($Client->plane_id == 1 || $Client->plane_id > 3));
                  if ($Ref_Prof->type == 0 || $valid_geo || $valid_hastag) { // Nivel de permisos dependendo do plano, solo para quem tem permissao para geo ou hastag
                    $ci->db_model->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
                  }
                }
              }
            } else {
              echo "Not reference profiles: $Client->login <br>\n<br>\n";
              if (count($Client->reference_profiles)) { // To keep unfollow
                $ci->db_model->insert_daily_work($Client->reference_profiles[0]->id, 0, $DIALY_REQUESTS_BY_CLIENT, $Client->cookies);
              }
              if (!$not_mail)
                $this->Gmail->send_client_not_rps($Client->email, $Client->name, $Client->login, $Client->pass);
            }
          }
          else if ($Client->status_id === user_status::ACTIVE) {
            $ci->db_model->set_client_cookies($Client->id);
            $ci->db_model->set_client_status($Client->id, user_status::VERIFY_ACCOUNT);
            $ci->db_model->insert_event_to_washdog($Client->client_id, washdog_type::ROBOT_VERIFY_ACCOUNT, 1, 0, "Cookies incompletas when prepare_daily_work");
          }
        } elseif (!$Client->paused) {
          // TODO: do something in Client autentication error
          // Send email to client
          $now = new \DateTime("now");
          $status_date = new \DateTime();
          $status_date->setTimestamp($Client->status_date ? $Client->status_date : 0);
          $diff_info = $status_date->diff($now);
          var_dump($diff_info->days);
          //if ($diff_info->days <= 3) {
          // TODO, UNCOMMENT
          if (!$not_mail)
            $this->Gmail->send_client_login_error($Client->email, $Client->name, $Client->login, $Client->pass);
          //}Jose 
        }
        //die("Alberto");
        // }
        //die("Loged all Clients");
        //
            //$ci->db_model->reset_preference_profile_cursors()
        //;
      }
    }

    // NUEVAS x IMPLMENTAR !!!
    public function prepare_client_daily_work(int $client_id, bool $not_mail = false) {
      
    }

    // NUEVAS x IMPLMENTAR !!!
    public function request_current_work(\business\cls\Client $client = NULL) {
      
    }

    // LISTA!!!
    public function do_work(int $client_id = NULL, int $n = NULL, int $rp = NULL) {
      $ci = &get_instance();
      try {
        $has_work = TRUE;
        $steps = 0;
        while ($has_work && ($n == NULL || $steps < $n)) {
          $steps++;
          //$DB = new \follows\cls\DB();
          //daily work: cookies reference_id to_follow last_access id insta_name insta_id client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following id name	login pass email telf role_id status_id	languaje 
          //echo '\n get follow work';
          if ($client_id == NULL) {
            $daily_work = $ci->db_model->get_follow_work();
          } else {
            $daily_work = $ci->db_model->get_follow_work_by_client_id($client_id, $rp);
          }
          //echo 'get follow work done';
          if ($daily_work) {
            $daily_work->login_data = json_decode($daily_work->cookies);
            if ($daily_work->login_data != NULL) {
              //Calculate time to sleep    
              //$last_access = DateTime::createFromFormat('U', $daily_work->last_access);
              //$now = DateTime::createFromFormat('U', time());
              //$diff_info = $last_access->diff($now);
              //$elapsed_time = $diff_info->i; // In minutes
              //$elapsed_time = (time() - intval($daily_work->last_access)) / 60.0 % 60.0; // minutes
              $now = time();
              $lst_acess = intval($daily_work->last_access);
              $elapsed_time = $now - $lst_acess; // sec
              if ($now > $lst_acess) {
                if ($elapsed_time < $GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME * 60) {
                  $now = \DateTime::createFromFormat('U', time());
                  $last_access = \DateTime::createFromFormat('U', $daily_work->last_access);
                  print "<br>_________ELAPSED TIME ($elapsed_time): ";
                  //print "<br>Last Access: " . $last_access->format('Y-m-d H:i:s') . "<br>";
                  //print "\$last_access = " . $daily_work->last_access . "<br>";
                  //print "\$elapsed_time = " . $elapsed_time . " min (" . intval(time() - intval($daily_work->last_access)) . " tics) <br>";
                  //print "\$To_Wait = " . intval($GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME * 60 - $elapsed_time) . " secs <br>";
                  sleep($GLOBALS['sistem_config']->MIN_NEXT_ATTEND_TIME * 60 - $elapsed_time); // secounds
                  //$now = \DateTime::createFromFormat('U', time());
                  //print "_________ELAPSE TIME: " . $now->format('Y-m-d H:i:s') . "<br>";
                }
                $this->do_follow_unfollow_work($daily_work);
              } else {
                sleep($lst_acess - $now);
              }
              //die("Test End!!");
            } else {
              print "<br> Login data NULL!!!!!!!!!!!! <br>";
            }
          } else {
            sleep(60 * 20);
          }
          //die("Test Ended!");
        }
        echo "<br>\n<br>\nCongratulations!!! Job done...!<br>\n";
      } catch (\Exception $exc) {
        echo $exc->getTraceAsString();
      }
    }

    // LISTA!!!
    private function do_client_work(DailyWork $daily_work) {
      $ci = &get_instance();
      if ($daily_work) {
        //Get new follows
        $unfollow_work = NULL;
        $Followeds_to_unfollow = array();
        if ($daily_work->to_unfollow > 0) {
          $unfollow_work = $ci->db_model->get_unfollow_work($daily_work->client_id);
          if (is_object($unfollow_work) && !is_bool($unfollow_work)) {
            while ($Followed = $unfollow_work->fetch_object()) {
              $To_Unfollow = new \follows\cls\Followed(); // Update Ref Prof Data
              $To_Unfollow->id = $Followed->id;
              $To_Unfollow->followed_id = $Followed->followed_id;
              $To_Unfollow->insta_name = $Followed->followed_login;
              array_push($Followeds_to_unfollow, $To_Unfollow);
            }
          }
        }
        //Reuest for the black list in the data base
        $daily_work->black_list = $ci->db_model->get_black_list($daily_work->client_id);
        $errors = false;
        $Ref_profile_follows = $this->Robot->do_follow_unfollow_work($Followeds_to_unfollow, $daily_work, $errors);
        $this->save_follow_unfollow_work($Followeds_to_unfollow, $Ref_profile_follows, $daily_work);
        //Count unfollows
        $unfollows = 0;
        foreach ($Followeds_to_unfollow as $unfollowed) {
          if ($unfollowed->unfollowed) {
            $unfollows++;
          }
        }
        // TODO: foults  
        $ci->db_model->update_daily_work($daily_work->reference_id, count($Ref_profile_follows), $unfollows, 0, $errors);
        return TRUE;
      }
      return FALSE;
    }

    // LISTA!!!
    public function get_work() {
      $ci = &get_instance();
      $daily_work = $ci->db_model->get_follow_work();
      $daily_work->login_data = json_decode($daily_work->cookies);
      $Followeds_to_unfollow = array();
      if ($daily_work->to_unfollow > 0) {
        $unfollow_work = $ci->db_model->get_unfollow_work($daily_work->client_id);
        while ($Followed = $unfollow_work->fetch_object()) { //
          $To_Unfollow = new \follows\cls\Followed();
          // Update Ref Prof Data
          $To_Unfollow->id = $Followed->id;
          $To_Unfollow->followed_id = $Followed->followed_id;
          array_push($Followeds_to_unfollow, $To_Unfollow);
        }
      }
      $daily_work->to_unfollow = $Followeds_to_unfollow;
      return $daily_work;
    }

    // LISTA!!!
    public function get_work_by_id(int $id) {
      $ci = &get_instance();
      $daily_work = $ci->db_model->get_follow_work_by_id($reference_id);
      $daily_work->login_data = json_decode($daily_work->cookies);
      $Followeds_to_unfollow = array();
      if ($daily_work->to_unfollow > 0) {
        $unfollow_work = $ci->db_model->get_unfollow_work($daily_work->client_id);
        while ($Followed = $unfollow_work->fetch_object()) { //
          $To_Unfollow = new \follows\cls\Followed();
          // Update Ref Prof Data
          $To_Unfollow->id = $Followed->id;
          $To_Unfollow->followed_id = $Followed->followed_id;
          array_push($Followeds_to_unfollow, $To_Unfollow);
        }
      }
      $daily_work->to_unfollow = $Followeds_to_unfollow;
      return $daily_work;
    }

    // LISTA!!!
    private function insert_daily_work(\BusinessRefProfile $ref_prof, $to_follow, $to_unfollow, $cookies) {
      $ci = &get_instance();
      $ci->db_model->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, json_encode($login_data));
    }

    // LISTA!!!
    private function delete_daily_work(int $ref_prof_id) {
      $ci = &get_instance();
      $ci->db_model->cmd_truncate_daily_work($ref_prof_id);
    }

    // LISTA!!!
    public function truncate_daily_work() {
      $ci = &get_instance();
      $ci->db_model->cmd_truncate_daily_work();
    }

  }

}
