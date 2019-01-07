<?php

//require_once 'Reference_profile[].php';

namespace business\cls {
    require_once 'User.php';
    /* require_once 'Robot.php';
      require_once 'DB.php';
      require_once 'StatusProfiles.php';
      require_once '../third_party/APIInstaWeb/InstaClient.php'; */

    /**
     * class Client
     * 
     */
    class Client extends User {
        /** Aggregation/s: */
        /** Compositions: */
        /** Attributes: */

        /**
         * 
         * @access public
         */
        public $credit_card_number;

        /**
         * 
         * @access public
         */
        public $credit_card_status_id;

        /**
         * 
         * @access public
         */
        public $credit_card_cvc;

        /**
         * 
         * @access public
         */
        public $credit_card_name;

        /**
         * 
         * @access public
         */
        public $pay_day;

        /**
         * 
         * @access public
         */
        public $plane_id;

        /**
         * 
         * @access public
         */
        public $insta_id;

        /**
         * 
         * @access public
         */
        public $status_date;

        /**
         * 
         * @access public
         */
        public $insta_followers_ini;

        /**
         * 
         * @access public
         */
        public $insta_following;

        /**
         * 
         * @access public
         */
        public $HTTP_SERVER_VARS;

        /**
         * 
         * @access public
         */
        public $cookies;

        /**
         * 
         * @access public
         */
        public $to_follow;

        /**
         * 
         * @access public
         */
        public $reference_profiles = array();

        /**
         *
         * @var type 
         */
        public $paused;

        /**
         *
         * @var Proxy 
         */
        public $Proxy;
        
        function __construct () {
          parent::__construct();
          
          $this->CI->load->model('db_model');
        }
        
        public function get_clients() {
            try {
                $Clients = array();
                //$DB = new \follows\cls\DB();
                $clients_data = $this->CI->db_model->get_clients_data();
                while ($client_data = $clients_data->fetch_object()) {
                    $Client = $this->fill_client_data($client_data);
                    array_push($Clients, $Client);
                }
                return $Clients;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function insert_clients_daily_report() {
            try {
                $Clients = array();
                //$DB = new \follows\cls\DB();
                $clients_data = $this->db_model->get_clients_data_for_report();
                while ($client_data = $clients_data->fetch_object()) {
                    $profile_data = (new Reference_profile())->get_insta_ref_prof_data($client_data->login, $client_data->id);
                    if ($profile_data) {
                        $result = $this->db_model->insert_client_daily_report($client_data->id, $profile_data);
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

        public function dumbu_statistics() {
            try {
                $Clients = array();
                //$DB = new \follows\cls\DB();
                $time = strtotime(date("Y-m-d H:00:00"));
                $datas = $this->db_model->get_dumbu_statistics();
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


                $datas2 = $this->db_model->get_dumbu_paying_customers();
                foreach ($datas2 as $value) {
                    $cols .= "PAYING_CUSTOMERS,";
                    $arr .= $value['cnt'] . ',';
                }



                $cols .= 'date)';
                $arr .= $time . ')';
                $this->db_model->insert_dumbu_statistics($cols, $arr);
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function fill_client_data($client_data) {
            $Client = NULL;
            if ($client_data) {
                $Client = new Client();
                //print_r($client_data);
                // Update Client Data
                $Client->id = $client_data->user_id;
                $Client->name = $client_data->name;
                $Client->login = $client_data->login;
                $Client->pass = $client_data->pass;
                $Client->email = $client_data->email;
                $Client->insta_id = $client_data->insta_id;
                $Client->plane_id = $client_data->plane_id;
                $Client->to_follow = isset($client_data->to_follow) ? $client_data->to_follow : 0;
                $Client->status_id = $client_data->status_id;
                $Client->insta_following = $client_data->insta_following;
                $Client->cookies = $client_data->cookies;
                $Client->paused = $client_data->paused;
                $Client->HTTP_SERVER_VARS = $client_data->HTTP_SERVER_VARS;
                $Client->init_date = $client_data->init_date;
                $Client->last_access = $client_data->last_access;
                $Client->get_reference_profiles($Client->id);
                $Client->Proxy = new Proxy();
                $Client->Proxy->load($client_data->proxy_id);
            }
            return $Client;
        }

        public function get_client($client_id) {
            try {
                //$DB = new DB();
                $client_data = $this->db_model->get_client_data($client_id);
                $Client = $this->fill_client_data($client_data);
                return $Client;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_begginer_client($client_id) {
            try {
                //$DB = new DB();
                $client_data = $this->db_model->get_biginner_data($client_id);
                $Client = $this->fill_client_data($client_data);
                return $Client;
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

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
                    $this->db_model->insert_daily_work($Ref_Prof->id, $to_follow, $to_unfollow, $Client->cookies);
                }
            } else {
                echo "Not reference profiles: $Client->login <br>\n<br>\n";
            }
        }

        public function rp_workable_count() {
            $count = 0;
            $Robot = new Robot();
            $proxy = $Robot->get_proxy_str($this);
            $status = new reference_profiles_status();
            //$DB = new DB();
            if ($this->reference_profiles) {
                foreach ($this->reference_profiles as $ref_prof) {
                    if ($ref_prof->deleted && $ref_prof->status != $status->DELETED) {
                        $this->db_model->UpdateReferenceProfileStatus($status->DELETED, $ref_prof->id);
                    } else if ($ref_prof->end_date != NULL && $ref_prof->status != $status->ENDED) {
                        $this->db_model->UpdateReferenceProfileStatus($status->ENDED, $ref_prof->id);
                    } else if (!$Robot->exist_reference_profile($ref_prof->insta_name, $ref_prof->type, $ref_prof->insta_id, json_decode($this->cookies), $proxy) && $ref_prof->status != $status->LOCKED) {
                        $this->db_model->UpdateReferenceProfileStatus($status->LOCKED, $ref_prof->id);
                    } else if ($ref_prof->status == $status->ACTIVE) {
                        $count++;
                    }
                }
            }
            return $count;
        }

        public function delete_daily_work($client_id) {
            //$DB = new DB();
            $this->db_model->delete_daily_work_client($client_id);
        }

        /**
         * 
         */
        function __construct() {
            parent::__construct();
            $this->CI->load->library('InstaApiLib');
        }

        /**
         * 
         * @param type Client ans Users tables data for a client.
         * @return logindata or NULL
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
                        $this->db_model->InsertEventToWashdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, "Unknow error with your request");
                    } else {
                        $this->db_model->InsertEventToWashdog($Client->id, washdog_type::PROBLEM_WITH_YOUR_REQUEST, 1, 0, $login_data->json_response->message);
                    }
                }
                $this->set_client_cookies($Client->id, NULL);
                return NULL;
            }
        }

// end of member function sign_in

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function check_insta_user() {
            
        }

        public function set_client_cookies($client_id = NULL, $cookies = NULL) {
            try {
                $client_id = $client_id ? $client_id : $this->id;
                $cookies = $cookies ? $cookies : $this->cookies;
                //$DB = new \follows\cls\DB();
                $result = $this->db_model->set_client_cookies($client_id, $cookies);
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

        public function set_client_status($client_id = NULL, $status_id = NULL) {
            try {
                $client_id = $client_id ? $client_id : $this->id;
                $status_id = $status_id ? $status_id : $this->status_id;
                //$DB = new \follows\cls\DB();
                $result = $this->db_model->set_client_status($client_id, $status_id);
                if ($result) {
                    print "Client $client_id to status $status_id!!!";
                } else {
                    print "FAIL CHANGING Client $client_id to status $status_id!!!";
                }
            } catch (Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

// end of member function sign_in

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function get_reference_profiles($client_id = NULL) {
            try {
                $client_id = $client_id ? $client_id : $this->id;
                //$DB = new \follows\cls\DB();
                $ref_profs_data = $this->db_model->get_reference_profiles_data($client_id);
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

        ///Anhadir estas funciones en el disenho
        public function checkpoint_requested(string $login, string $pass, \ApiInstaWeb\VerificationChoice $choise = \ApiInstaWeb\VerificationChoice::Email) {
            $login_data = json_decode($this->cookies);
            $proxy = $this->GetProxy();
            $client = new \ApiInstaWeb\InstaClient($this->insta_id, $login_data, $proxy);
            $res = $client->checkpoint_requested($login, $pass, $choise);
            $this->cookies = json_encode($client->cookies);
            //guardar las cookies en la Base de Datos
            return $res;
        }

        public function make_checkpoint(string $login, string $code) {
            //las cookies son las actualizadas de la BD
            $login_data = json_decode($this->cookies);
            $proxy = $this->GetProxy();
            $client = new \ApiInstaWeb\InstaClient($this->insta_id, $login_data, $proxy);
            $res = $client->make_checkpoint($login, $code);
            $this->cookies = json_encode($client->cookies);
            //guardar las cookies en la Base de Datos
            return $res;
        }

        public function GetProxy() {
            
        }

    }

    // end of Client
}
?>
