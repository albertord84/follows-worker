<?php

namespace follows\cls {
    require_once 'DB.php';
    require_once 'Gmail.php';
    require_once 'Reference_profile.php';
    require_once 'Day_client_work.php';
    require_once 'washdog_type.php';
    require_once 'system_config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/utils.php';
    require_once 'InstaAPI.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';

//    require_once '../libraries/webdriver/phpwebdriver/WebDriver.php';
//    echo $_SERVER['DOCUMENT_ROOT'];
//    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/libraries/webdriver/phpwebdriver/WebDriver.php';
    /**
     * class Robot
     * 
     */
    class Robot {
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access public
         */
        public $id;

        /**
         * 
         * @access public
         */
        public $IP;

        /**
         * 
         * @access public
         */
        public $IPS;

        /**
         * 
         * @access public
         */
        public $dir;

        /**
         * 
         * @access public
         */
        public $config;

        /**
         * 
         * @access public
         */
        public $daily_work;

        /**
         * 
         * @access public
         */
        public $Ref_profile;

        /**
         *
         * @var type 
         */
        public $csrftoken = NULL;

        function __construct($DB = NULL, $conf_file = "/../../../FOLLOWS.INI", $id = -1) {
            $config = parse_ini_file(dirname(__FILE__) . $conf_file, true);
            $this->IPS = $config["IPS"];
            $this->Day_client_work = new Day_client_work();
            $this->Ref_profile = new Reference_profile();
            $this->DB = $DB ? $DB : new \follows\cls\DB();
        }

        // end of member function login_client
        /**
         * 
         *
         * @param Day_client_work Day_client_work 
         * @param Reference_profile Ref_profile 
         * @return void
         * @access public
         */
        public function do_follow_unfollow_work($Followeds_to_unfollow, $daily_work, &$error = FALSE) {
            //$this->Day_client_work = $Day_client_work;
            //$this->Ref_profile = $Ref_profile;
            //$DB = new DB();
            $Client = (new \follows\cls\Client())->get_client($daily_work->client_id);
            $this->daily_work = $daily_work;
            $login_data = $daily_work->login_data;
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();

            // Do unfollow work
            $has_next = count($Followeds_to_unfollow);
            echo "<br>\nClient: $daily_work->client_id <br>\n";
            echo "<br>\nnRef Profil: $daily_work->insta_name<br>\n" . " Count: " . count($Followeds_to_unfollow) . " Hasnext: $has_next - ";
            echo date("Y-m-d h:i:sa");
            echo "<br>\n make_insta_friendships_command UNFOLLOW <br>\n";

            for ($i = 0; $i < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && ($has_next); $i++) {
                $error = FALSE;
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($Followeds_to_unfollow);
                $Profile->unfollowed = FALSE;
                $curl_str = "";
                $json_response = $this->make_insta_friendships_command(
                        $login_data, $Profile->followed_id, 'unfollow', 'web/friendships', $Client, $curl_str
                );
                if ($json_response === NULL) {
                    $result = $this->DB->delete_daily_work_client($daily_work->client_id);
                    //$this->DB->set_client_cookies($daily_work->client_id);
                    $this->DB->set_client_status($daily_work->client_id, user_status::BLOCKED_BY_TIME);
                    $this->DB->InsertEventToWashdog($daily_work->client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id, "Respuesta incompleta: $curl_str");
                    $error = TRUE;
                    var_dump($curl_str);
                    var_dump("Error in do_follow_unfollow_work!!! unfollow");
                } else if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    $Profile->unfollowed = TRUE;
                    var_dump($json_response);
                    echo "Followed ID: $Profile->followed_id";
                    if(isset($Profile->insta_name)) {echo "($Profile->insta_name)";}
                    echo "<br>\n";
                    // Mark it unfollowed and send back to queue
                    // If have some Profile to unfollow
                    $has_next = count($Followeds_to_unfollow) && !$Followeds_to_unfollow[0]->unfollowed;
                } else {
                    echo "ID: $Profile->followed_id ($Profile->insta_name)<br>\n";
//                    var_dump($json_response);
                    $error = $this->process_follow_error($json_response);
                    // TODO: Class for error messages
                    if ($error == 6) {// Just empty message:
                        $error = FALSE;
                        $Profile->unfollowed = TRUE;
                    } else if ($error == 7 || $error == 9) { // To much request response string only
                        $error = FALSE;
                        break;
                    } else if ($error == 10) {
                        (new Gmail())->sendAuthenticationErrorMail($Client->name, $Client->email);
                    } else {
                        break;
                    }
                }
                array_push($Followeds_to_unfollow, $Profile);
                if($daily_work->client_id == 30864)
                {
                    sleep(23);
                }
            }

            // Do follow work
            //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
            $Ref_profile_follows = array();
            $follows = 0;
            echo "<br>\nmake_insta_friendships_command FOLLOW (like firsts = $daily_work->like_first): $daily_work->to_follow <br>\n";
            if (!$error && $daily_work->to_follow > 0) { // If has to follow
                $get_followers_count = 0;
                $error = FALSE;
                while (!$error && $follows < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && $get_followers_count < $GLOBALS['sistem_config']->MAX_GET_FOLLOWERS_REQUESTS) {
                    // Get next insta followers of Ref_profile
                    $get_followers_count++;
                    echo "<br>\nRef Profil: $daily_work->insta_name (id: $daily_work->rp_id | type: $daily_work->type)<br>\n";
                    // Get Users 
                    $page_info = NULL;
                    $Profiles = $this->get_profiles_to_follow($daily_work, $error, $page_info);
                    //var_dump($Profiles);
                    foreach ($Profiles as $Profile) {
                        $Profile = $Profile->node;
                        echo "Profil name: $Profile->username ";
                        $null_picture = strpos($Profile->profile_pic_url, '11906329_960233084022564_1448528159_a');
                        // Check if its a valid profile
//                            $valid_profile = FALSE;
//                            if (!$is_private) {
//                                // Check the post amount from this profile
//                                $MIN_FOLLOWER_POSTS = $GLOBALS['sistem_config']->MIN_FOLLOWER_POSTS;
//                                $posts = $this->get_insta_chaining($login_data, $Profile->id, $MIN_FOLLOWER_POSTS);
//                                $valid_profile = count($posts) >= $MIN_FOLLOWER_POSTS;
//                            } else {
//                                $valid_profile = TRUE;
//                            } //                            if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer && $valid_profile) { // If user not requested or follwed by Client
                        if (!$Profile->requested_by_viewer && !$Profile->followed_by_viewer && !$null_picture) { // If profile not requested or follwed by Client
                            $Profile_data = $this->get_reference_user($login_data, $Profile->username);
                            $is_private = isset($Profile_data->user->is_private) ? $Profile_data->user->is_private : false;
                            $posts_count = isset($Profile_data->user->media->count) ? $Profile_data->user->media->count : 0;
                            $MIN_FOLLOWER_POSTS = $GLOBALS['sistem_config']->MIN_FOLLOWER_POSTS;
                            $valid_profile = true; //$posts_count >= $MIN_FOLLOWER_POSTS;
                            if (isset($Profile->id) && $Profile->id != "") {
                                //check if the profile is in the black list
                                if (isset($daily_work->black_list) && str_binary_search($Profile->id, $daily_work->black_list)) {
                                    $valid_profile = false;
                                }
                                $following_me = (isset($Profile_data->user->follows_viewer)) ? $Profile_data->user->follows_viewer : false;
                                // TODO: BUSCAR EN BD QUE NO HALLA SEGUIDO ESA PERSONA
                                $followed_in_db = $this->DB->is_profile_followed_db2($daily_work->client_id, $Profile->id);
                                //$followed_in_db = NULL;
                                if (!$followed_in_db && !$following_me && $valid_profile) { // Si no lo he seguido en BD y no me está siguiendo
                                    // Do follow request
                                    echo "FOLLOWING <br>\n";
                                    $curl_str = "";
                                    $json_response2 = $this->make_insta_friendships_command($login_data, $Profile->id, 'follow', 'web/friendships', $Client, $curl_str);
                                    if ($json_response2 === NULL) {
                                        $result = $this->DB->delete_daily_work_client($daily_work->client_id);
                                        //$this->DB->set_client_cookies($daily_work->client_id);
                                        $this->DB->set_client_status($daily_work->client_id, user_status::BLOCKED_BY_TIME);
                                        $curl_str = json_encode($curl_str);
                                        $this->DB->InsertEventToWashdog($daily_work->client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id, "Respuesta incompleta: $curl_str");
                                        $error = TRUE;
                                        var_dump($curl_str);
                                        var_dump("Error in do_follow_unfollow_work!!! follow");
                                    }
                                     if (is_object($json_response2) && $json_response2->status == 'ok') { // if response is ok
                                        array_push($Ref_profile_follows, $Profile);                                        
                                        $follows++;
                                        if ($daily_work->like_first /* && count($Profile_data->graphql->user->media->nodes) */) {
                                            //$json_response_like = $this->make_insta_friendships_command($login_data, $Profile_data->user->media->nodes[0]->id, 'like', 'web/likes');
                                            if($daily_work->client_id == 30864)
                                            {
                                                sleep(29);
                                            }
                                            $json_response_like = $this->like_fist_post($login_data, $Profile->id, $Client);
                                            if (!is_object($json_response_like) || !isset($json_response_like->status) || $json_response_like->status != 'ok') {
                                                $error = $this->process_follow_error($json_response_like);
                                                var_dump($json_response_like);
                                                $error = TRUE;
                                                if ($error == 10) {
                                                    (new Gmail())->sendAuthenticationErrorMail($Client->name, $Client->email);
                                                }
                                            }
                                        }                                   
                                        if ($follows >= $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME)
                                            break;
                                    } else {
                                        $error = $this->process_follow_error($json_response2);
                                        var_dump($json_response2);
                                        $error = TRUE;
                                        if ($error == 10) {
                                            (new Gmail())->sendAuthenticationErrorMail($Client->name, $Client->email);
                                        }
                                        break;
                                    }                                   
                                    // Sleep up to proper delay between request
                                    sleep($GLOBALS['sistem_config']->DELAY_BETWEEN_REQUESTS);
                                    if($daily_work->client_id == 30864)
                                    {
                                        sleep(39);
                                    }
                                } else {
                                    echo "NOT FOLLOWING: followed_in_db($followed_in_db) following_me($following_me) valid_profile($valid_profile)<br>\n";
                                }
                            } else {
                                echo "Wrong profile to FOLLOW: $Profile <br>\n";
                            }
                        } else {
                            echo "NOT FOLLOWING: requested_by_viewer($Profile->requested_by_viewer) followed_by_viewer($Profile->followed_by_viewer) null_picture($null_picture)<br>\n";
                        }
                    }
                    // Update cursor
                    if ($page_info && isset($page_info->end_cursor)) {
                        $daily_work->insta_follower_cursor = $page_info->end_cursor;
                        $this->DB->update_reference_cursor($daily_work->reference_id, $page_info->end_cursor);
                        if (!$page_info->has_next_page)
                            break;
                    } else {
                        break;
                    }
                }
            }
            echo "<br><br>\n\n________________________________________________<br><br>\n\n";
            return $Ref_profile_follows;
        }

        /*
          public function do_unfollow_work($Followeds_to_unfollow)
          {
          $error = FALSE;
          $limit = $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME;
          $has_next = count($Followeds_to_unfollow);
          $login_data = $this->daily_work->login_data;
          for ($i = 0; $i < $limit && ($has_next); $i++) {
          // Next profile to unfollow, not yet unfollwed
          $Profile = array_shift($Followeds_to_unfollow);
          $Profile->unfollowed = FALSE;
          $json_response = $this->make_insta_friendships_command(
          $login_data, $Profile->followed_id, 'unfollow'
          );
          if (is_object($json_response) && $json_response->status == 'ok') {
          // if unfollowed
          $Profile->unfollowed = TRUE;
          var_dump(json_encode($json_response));
          echo "Followed ID: $Profile->followed_id<br>\n";
          // Mark it unfollowed and send back to queue
          // If have some Profile to unfollow
          $has_next = count($Followeds_to_unfollow) && !$Followeds_to_unfollow[0]->unfollowed;
          } else {
          echo "ID: $Profile->followed_id<br>\n";
          //                    var_dump($json_response);
          $error = $this->process_follow_error($json_response);
          // TODO: Class for error messages
          if ($error == 6) {// Just empty message:
          $error = FALSE;
          $Profile->unfollowed = TRUE;
          } else if ($error == 7 || $error == 9) { // To much request response string only
          $error = FALSE;
          break;
          } else {
          break;
          }
          }
          array_push($Followeds_to_unfollow, $Profile);
          }
          }
          public function do_follow_work($Followeds_to_unfollow)
          {}
         */

        public function get_profiles_to_follow($daily_work, $error, &$page_info) {
            $Profiles = array();
            $error = TRUE;
            $login_data = json_decode($daily_work->cookies);
            $quantity = min(array($daily_work->to_follow, $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME));
            $page_info = new \stdClass();
            $Client = (new \follows\cls\Client())->get_client($daily_work->client_id);
            $proxy = $this->get_proxy_str($Client);
            if ($daily_work->rp_type == 0) {
                echo "<br>\nRef Profil: $daily_work->insta_name<br>\n";
                $json_response = $this->get_insta_followers(
                        $login_data, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor, $proxy
                );
                //var_dump($json_response);
                if ($json_response === NULL) {
                    var_dump("<br>\n Empty response getting followers from this profile... <br>\n");                   
                    $json_response = $this->process_get_followers_error($daily_work, $Client, $quantity, $proxy);
                }
                if (is_object($json_response) && $json_response->status == 'ok') {
                    if (isset($json_response->data->user->edge_followed_by)) { // if response is ok
                        echo "Nodes: " . count($json_response->data->user->edge_followed_by->edges) . " <br>\n";
                        $page_info = $json_response->data->user->edge_followed_by->page_info;
                        $Profiles = $json_response->data->user->edge_followed_by->edges;
                        //$DB = new DB();
                        if ($page_info->has_next_page === FALSE && $page_info->end_cursor != NULL) { // Solo qdo es <> de null es que llego al final
                            $this->DB->update_reference_cursor($daily_work->reference_id, NULL);
                            echo ("<br>\n Updated Reference Cursor to NULL!!");
                            $result = $this->DB->delete_daily_work($daily_work->reference_id);
                            if ($result) {
                                echo ("<br>\n Deleted Daily work!! Ref $daily_work->reference_id");
                            }
                        } else if ($page_info->has_next_page === FALSE && $page_info->end_cursor === NULL) {
//                            $Client = new Client();
//                            $Client = $Client->get_client($daily_work->user_id);
//                            $login_result = $Client->sign_in($Client);
                            $this->DB->update_reference_cursor($daily_work->reference_id, NULL);
                            echo ("<br>\n Updated Reference Cursor to NULL!!");
                            $result = $this->DB->delete_daily_work($daily_work->reference_id);
                            if ($result) {
                                echo ("<br>\n Deleted Daily work!! Ref $daily_work->reference_id");
                            }
                        }
                        $error = FALSE;
                    } else {
                        $page_info->end_cursor = NULL;
                        $page_info->has_next_page = false;
                    }
                }
            } else if ($daily_work->rp_type == 1) {
                $json_response = $this->get_insta_geomedia($login_data, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor, $proxy);
                if (is_object($json_response) && $json_response->status == 'ok') {
                    if (isset($json_response->data->location->edge_location_to_media)) { // if response is ok
                        echo "Nodes: " . count($json_response->data->location->edge_location_to_media->edges) . " <br>\n";
                        $page_info = $json_response->data->location->edge_location_to_media->page_info;
                        foreach ($json_response->data->location->edge_location_to_media->edges as $Edge) {
                            $profile = new \stdClass();
                            $profile->node = $this->get_geo_post_user_info($login_data, $daily_work->rp_insta_id, $Edge->node->shortcode, $proxy);
                            array_push($Profiles, $profile);
                        }
                        $error = FALSE;
                    } else {
                        $page_info->end_cursor = NULL;
                        $page_info->has_next_page = false;
                    }
                }
            } else if ($daily_work->rp_type == 2) {
                $json_response = $this->get_insta_tagmedia($login_data, $daily_work->insta_name, $quantity, $daily_work->insta_follower_cursor, $proxy);
                if (is_object($json_response)) {
                    if (isset($json_response->data->hashtag->edge_hashtag_to_media)) { // if response is ok
                        echo "Nodes: " . count($json_response->data->hashtag->edge_hashtag_to_media->edges) . " <br>\n";
                        $page_info = $json_response->data->hashtag->edge_hashtag_to_media->page_info;
                        foreach ($json_response->data->hashtag->edge_hashtag_to_media->edges as $Edge) {
                            $profile = new \stdClass();
                            $profile->node = $this->get_tag_post_user_info($login_data, $Edge->node->shortcode, $proxy);
                            array_push($Profiles, $profile);
                        }
                        $error = FALSE;
                    } else {
                        $page_info->end_cursor = NULL;
                        $page_info->has_next_page = false;
                    }
                }
            }
            if ($error) {
                $error = $this->process_follow_error($json_response);
            }
            return $Profiles;
        }

// end of member function do_follow_unfollow_work
        function process_follow_error($json_response) {
            //$DB = new DB();
            $Profile = new Profile();
            $ref_prof_id = $this->daily_work->rp_id;
            $client_id = $this->daily_work->client_id;
            $error = $Profile->parse_profile_follow_errors($json_response);
            switch ($error) {
                case 1: // "Com base no uso anterior deste recurso, sua conta foi impedida temporariamente de executar essa ação. Esse bloqueio expirará em há 23 horas."
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_INSTA!!! <br>\n";
                    $result = $this->DB->delete_daily_work_client($client_id);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    break;
                case 2: // "Você atingiu o limite máximo de contas para seguir. É necessário deixar de seguir algumas para começar a seguir outras."
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::SET_TO_UNFOLLOW, 1, $this->id);
                    $this->DB->set_client_status($client_id, user_status::UNFOLLOW);
                    print "<br>\n Client (id: $client_id) set to UNFOLLOW!!! <br>\n";
//                    print "<br>\n Client (id: $client_id) MUST set to UNFOLLOW!!! <br>\n";
                    break;
                case 3: // "Unautorized"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    $this->SetUnautorizedClientStatus($client_id);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_INSTA!!! <br>\n";
                    break;
                case 4: // "Parece que você estava usando este recurso de forma indevida"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_TIME!!! <br>\n";
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    // Alert when insta block by IP
                    $result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    $rows_count = $result->num_rows;
                    if ($rows_count == 100 || $rows_count == 150 || ($rows_count >= 200 && $rows_count <= 210)) {
                        $Gmail = new Gmail();
                        $Gmail->send_client_login_error("josergm86@gmail.com", "Jose!!!!!!! BLOQUEADOS 4= " . $rows_count, "Jose");
                        $Gmail->send_client_login_error("ruslan.guerra88@gmail.com", "Ruslan!!!!!!! BLOQUEADOS 4= " . $rows_count, "Ruslan");
                    }
                    print "<br>\n BLOCKED_BY_TIME!!! number($rows_count) <br>\n";
                    break;
                case 5: // "checkpoint_required"
                    $result = $this->DB->delete_daily_work_client($client_id);
                    var_dump($result);
                    $this->DB->set_client_status($client_id, user_status::VERIFY_ACCOUNT);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::ROBOT_VERIFY_ACCOUNT, 1, $this->id);
                    $this->DB->set_client_cookies($client_id, NULL);
                    print "<br>\n Unautorized Client (id: $client_id) set to VERIFY_ACCOUNT!!! <br>\n";
                    break;
                case 6: // "" Empty message
                    print "<br>\n Empty message (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;
                case 7: // "Há solicitações demais. Tente novamente mais tarde." "Aguarde alguns minutos antes de tentar novamente."
                    print "<br>\n Há solicitações demais. Tente novamente mais tarde. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    //$result = $this->DB->delete_daily_work_client($client_id);
                    //$this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
//                    var_dump($result);
//                    print "<br>\n Unautorized Client (id: $client_id) STUDING set it to BLOCKED_BY_TIME!!! <br>\n";
                    // Alert when insta block by IP
                   // $time = $GLOBALS['sistem_config']->INCREASE_CLIENT_LAST_ACCESS;
                    
                    
                    // @TODO: Revisar Jose Angel
                    $proxy = $this->DB->get_client_proxy($client_id);
                    $new_proxy = ($proxy->idProxy + rand(0,6)) % 8 + 1;
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::SET_PROXY, 1, $this->id, "proxy set from proxy $proxy->idProxy to $new_proxy");
                    var_dump("Set Proxy ($proxy->idProxy) of client ($client_id) to proxy ($new_proxy)\n" );
                    $this->DB->SetProxyToClient($client_id,$new_proxy);                
                    
                    
                   // $this->DB->Increase_Client_Last_Access($client_id, 1);

                    //$result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    /* $result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                      $rows_count = $result->num_rows;
                      if ($rows_count == 100 || $rows_count == 150 || ($rows_count >= 200 && $rows_count <= 205)) {
                      $Gmail = new Gmail();
                      $Gmail->send_client_login_error("josergm86@gmail.com", "Jose!!!!!!! BLOQUEADOS 1= " . $rows_count, "Jose");
                      $Gmail->send_client_login_error("ruslan.guerra88@gmail.com", "Ruslan!!!!!!! BLOQUEADOS 1= " . $rows_count, "Ruslan");
                      } */
                    break;
                case 8: // "Esta mensagem contém conteúdo que foi bloqueado pelos nossos sistemas de segurança." 
                    $result = $this->DB->delete_daily_work_client($client_id);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    //var_dump($result);
                    print "<br>\n Esta mensagem contém conteúdo que foi bloqueado pelos nossos sistemas de segurança. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;
                case 9: // "Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde." 
                    print "<br>\n Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;
                case 10:
                    print "<br> Empty response from instagram</br>";
                    $time = $GLOBALS['sistem_config']->INCREASE_CLIENT_LAST_ACCESS;
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id, "access incresed in $time");

                    $this->DB->Increase_Client_Last_Access($client_id, $GLOBALS['sistem_config']->INCREASE_CLIENT_LAST_ACCESS);

                    $result = $this->DB->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    break;
                case 11:
                    print "<br> se ha bloqueado. Vuelve a intentarlo</br>";
                    $result = $this->DB->delete_daily_work_client($client_id);
                    //$this->DB->set_client_cookies($client_id);                    
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    break;
                case 12:
                    $result = $this->DB->update_reference_cursor($ref_prof_id, NULL);
                    print "<br>$ref_prof_id set to null<br>\n";
                    break;
                default:
                    print "<br>\n Client (id: $client_id) not error code found ($error)!!! <br>\n";
//                    $result = $this->DB->delete_daily_work_client($client_id);
//                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
//                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    $error = FALSE;
                    break;
            }
            return $error;
        }

        /**
         * Friendships API commands, normally used to 'follow' and 'unfollow'.
         * @param type $login_data
         * @param type $resource_id {ex: Profile Id (ds_userId)}
         * @param type $command {follow, unfollow, ... }
         * @return type
         */
        public function make_insta_friendships_command($login_data, $resource_id, $command = 'follow', $objetive_url = 'web/friendships', $Client = NULL, &$curl_str = "") {
            $ip = NULL;
            $ip_count = -1;
            $size = count($this->IPS['IPS']);
            $visited = array_fill(0, $size, FALSE);

            while ($ip_count < 0) {
                $ip_count++;
                $proxy = $this->get_proxy_str($Client);
                $curl_str = $this->make_curl_friendships_command_str("'https://www.instagram.com/$objetive_url/$resource_id/$command/'", $login_data, $proxy, $Client, $ip);
                //print("<br><br>$curl_str<br><br>");
                //echo "<br><br><br>O seguidor ".$user." foi requisitado. Resultado: ";
                if ($curl_str === NULL) {
                    var_dump("cookies are wrong in line 545\n");
                    return NULL;
                }
                exec($curl_str, $output, $status);                
                
                //echo "echo test: $ip_count \n";
                //var_dump($output);
                if (is_array($output) && count($output)) {
                    $json_response = json_decode($output[count($output) - 1]);
                    if ($json_response && (isset($json_response->result) || (isset($json_response->status) && $json_response->status === 'ok'))) {
                        if ($ip_count > -1) {
                            $HTTP_SERVER_VARS = NULL;
                            if (isset($Client->HTTP_SERVER_VARS)) { // if 
                                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
                                $HTTP_SERVER_VARS->SERVER_ADDR = $ip;
                            } else {
                                $HTTP_SERVER_VARS = new \stdClass();
                                $HTTP_SERVER_VARS->SERVER_ADDR = $ip;
                            }
                            //(new \follows\cls\DB())->SaveHttpServerVars($Client->id, json_encode($HTTP_SERVER_VARS));
                        }
                        return $json_response;
                    } else {                        
                        echo "Error in line 562</br>\n";                        
                        var_dump($output);
                        var_dump($curl_str);
                        return ($json_response === NULL) ? $output : $json_response;
                    }
                } else {                    
                    echo "Error in line 567</br>\n"; 
                    var_dump($output); 
                    var_dump($curl_str);
                    return $output;
                }
            }
            return NULL;
        }

        public function make_api_insta_friendships($login_data, $resource_id, $command = 'follow', $objetive_url = 'web/friendships', $Client = NULL) {
            
        }

        public function make_insta_friendships_command_client($Client, $resource_id, $command = 'follow', $objetive_url = 'web/friendships') {
            $login_data = json_decode($Client->login_data);

            $proxy = $this->get_proxy_str($Client);

            $curl_str = $this->make_curl_friendships_command_str("'https://www.instagram.com/$objetive_url/$resource_id/$command/'", $login_data, $proxy);
            if ($curl_str === NULL)
                return NULL;
            //print("<br><br>$curl_str<br><brx>");
            //echo "<br
            //><br><br>O seguidor ".$user." foi requisitado. Resultado: ";
            exec($curl_str, $output, $status);
            if (is_array($output) && count($output)) {
                $json_response = json_decode($output[0]);
                if ($json_response && (isset($json_response->result) || isset($json_response->status))) {
                    return $json_response;
                }
            }
//            else
//            {
//                try{
//                    $curl_str = $this->make_curl_friendships_command_str2("'https://www.instagram.com/$objetive_url/$resource_id/$command/'", $login_data);
//                    exec($curl_str, $output, $status);
//                    if (is_array($output) && count($output)) {
//                        $json_response = json_decode($output[0]);
//                        if ($json_response && (isset($json_response->result) || isset($json_response->status))) {
//                            return $json_response;
//                        }
//                    }
//                    else{
//                           $this->temporal_log("--------following error-----");
//                           $this->temporal_log('$curl_str');
//                           $this->temporal_log(json_encode($output));
//                           $this->temporal_log(json_encode($login_data));
//                           $this->temporal_log("--------end following error-----");                        
//                    }
//                }catch(\Exception $exc){}
//            }
            return $output;
            //print_r($status);
            //print("-> $status<br><br>");
//            return $json_response;
        }

        public function make_curl_friendships_command_str($url, $login_data, $proxy = NULL, $Client = NULL, $ip = NULL) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;

            $curl_str = "curl $proxy $url ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid;  csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            //"s_network=; ig_pr=1; ig_vw=1855;;
            $curl_str .= "-H 'origin: www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
                $curl_str .= "-H 'X-Instagram-Ajax: dad8d866382b' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Content-Length: 0' ";
            $curl_str .= "--compressed ";
            /* if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL && $ip === NULL) { // if 
              $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
              $ip = $HTTP_SERVER_VARS->SERVER_ADDR;
              $curl_str .= "--interface $ip";
              }
              else */ //if($ip !== NULL && $ip !== -1)
//            {
//                $curl_str .= "--interface $ip";
//            }12
            return $curl_str;
        }

        public function make_curl_friendships_command_str2($url, $login_data, $Client = NULL) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            $ip = "";
            if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
            }
            $curl_str = "curl $url ";
            $curl_str .= "-X POST ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid;  csrftoken=$csrftoken; ds_user_id=$ds_user_id'; ";
            $curl_str .= "urlgen=\"{\\\"time\\\": 1517542522\054 \\\"$ip\\\": 27725}:1ehUaS:pU706C7s0daOT9gPk0yvLeUWKMA\"";
            //"s_network=; ig_pr=1; ig_vw=1855;;
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-Ajax: 1' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Content-Length: 0' ";
            $curl_str .= "--compressed --interface $ip ";
            //$curl_str .= "--compressed";
            return $curl_str;
        }

        public function get_insta_chaining($login_data, $user, $N = 1, $cursor = NULL, $proxy = "") {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_chaining_str("$url", $login_data, $user, $N, $cursor, $proxy);
                if ($curl_str === NULL)
                {   
                    var_dump("error in cookies line 708 \n");
                    return NULL;
                }
                //print("<br><br>$curl_str<br><br>");
                exec($curl_str, $output, $status);
                //print_r($output);
                //print("-> $status<br><br>");
                $json = json_decode($output[0]);
                if($json == NULL)
                {
                    echo "Line error in line 718 in get_insta_chaining</br>\n";
                    var_dump($output);
                    var_dump($curl_str);                    
                }
                if (isset($json->data->user->edge_owner_to_timeline_media) && isset($json->data->user->edge_owner_to_timeline_media->edges) && count($json->data->user->edge_owner_to_timeline_media->edges)) {
                    return $json->data->user->edge_owner_to_timeline_media->edges;
                }
                return FALSE;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_insta_followers($login_data, $user, $N, $cursor = NULL, $proxy = "") {
            try {

                $tag_query = '37479f2b8209594dde7facb0d904896a';
                $variables = "{\"id\":\"$user\",\"first\":$N";
                if($cursor == NULL || $cursor == "NULL")
                    $variables .= ",\"after\":\"$cursor\"}";
                else {$variables .= "}"; }
                $curl_str = $this->make_curl_followers_query($tag_query, $variables, $login_data, $proxy);
                if ($curl_str === NULL)
                    return NULL;
                exec($curl_str, $output, $status);
                //echo "<br>output $output[0] \n\n</br>";
                //print_r($output);
                //print("-> $status<br><br>");                
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json->data->user->edge_followed_by) && isset($json->data->user->edge_followed_by->page_info)) {
                    if ($json->data->user->edge_followed_by->page_info->has_next_page === false) {
                        echo ("<br>\n END Cursor empty!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>\n ");
                        var_dump(json_encode($json));
                        //$DB = new DB();
                        $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                        echo ("<br>\n Updated Reference Cursor to NULL!!<br>\n ");
                        $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                        if ($result) {
                            echo ("<br>\n Deleted Daily work!!<br>\n ");
                        }
                    }
                } else {
                    var_dump($output);
                    print_r($curl_str);
                    /* if (isset($json->data) && ($json->data->user == null)) {
                      //$this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                      //echo ("<br>\n Updated Reference Cursor to NULL!!");
                      $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                      if ($result) {
                      echo ("<br>\n Deleted Daily work!!<br>\n ");
                      } else {
                      var_dump($result);
                      }
                      } */
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        /**
         * Unfollow Total
         * @param type $login_data
         * @param type $user
         * @param type $N
         * @param type $cursor  
         * @return type
         */
        public function get_insta_follows($login_data, $user, $N, &$cursor = NULL) {
            try {
                $url = "https://www.instagram.com/graphql/query/";
                $curl_str = $this->make_curl_follows_str("$url", $login_data, $user, $N, $cursor);
                if ($curl_str === NULL)
                    return NULL;
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
                //var_dump($json);
                if (isset($json->data->user->edge_follow) && isset($json->data->user->edge_follow->page_info)) {
                    $cursor = $json->data->user->edge_follow->page_info->end_cursor;
                    if (count($json->data->user->edge_follow->edges) == 0) {
                        var_dump($json);
//                        var_dump($curl_str);
                        echo ("<br>\n No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!<br>\n ");
                    }
                } else {
                    //var_dump($output);
                    var_dump($curl_str);
                    //$this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                }
                return $json;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function get_insta_geomedia($login_data, $location, $N, &$cursor = NULL, $proxy = "", $without_log = false) {
            try {

                $tag_query = 'ac38b90f0f3981c42092016a37c59bf7';                
                
                $variables = "{\"id\":\"$location\",\"first\":$N";
                if($cursor != NULL && $cursor != "NULL")
                {
                    $variables .= ",\"after\":\"$cursor\"";
                }
                $variables .= "}"; 
                
                $curl_str = $this->make_curl_followers_query($tag_query, $variables, $login_data, $proxy);
                if ($curl_str === NULL)
                    return NULL;
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json->data->location->edge_location_to_media) && isset($json->data->location->edge_location_to_media->page_info)) {
                    $cursor = $json->data->location->edge_location_to_media->page_info->end_cursor;
                    if (count($json->data->location->edge_location_to_media->edges) == 0) {
                        //echo '<pre>'.json_encode($json, JSON_PRETTY_PRINT).'</pre>';
                        //var_dump($json);
//                        var_dump($curl_str);
                        if (!$without_log)
                            echo ("<br>\n No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                        $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                        $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                        if (!$without_log)
                            echo ("<br>\n Set end cursor to NULL!!!!!!!! Deleted daily work!!!!!!!!!!!!");
                    }
                } else if (isset($json->data) && $json->data->location == NULL) {
                    //var_dump($output);
                    if (!$without_log)
                        print_r($curl_str);
                    $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                    $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                    if (!$without_log)
                        echo ("<br>\n Set end cursor to NULL!!!!!!!! Deleted daily work!!!!!!!!!!!!");
                } else {
                    var_dump($output);
                    print_r($curl_str);
                    if (!$without_log)
                        echo ("<br>\n Untrated error!!!");
                }
                return $json;
            } catch (\Exception $exc) {
                if (!$without_log)
                    echo $exc->getTraceAsString();
            }
        }

        public function get_insta_tagmedia($login_data, $tag, $N, &$cursor = NULL, $proxy = "", $without_log = false) {
            try {
                $tag_query = 'ded47faa9a1aaded10161a2ff32abb6b';
                
                  
                $variables = "{\"tag_name\":\"$tag\",\"first\":2";
                if($cursor != NULL && $cursor != "NULL")
                {
                    $variables .= ",\"after\":\"$cursor\"";                 
                }
                $variables .= "}"; 
                
                
                $curl_str = $this->make_curl_followers_query($tag_query, $variables, $login_data, $proxy);
                if ($curl_str === NULL)
                    return NULL;
                exec($curl_str, $output, $status);
                $json = json_decode($output[0]);
                //var_dump($output);
                if (isset($json) && $json->status == 'ok') {
                    if (isset($json->data->hashtag->edge_hashtag_to_media) && isset($json->data->hashtag->edge_hashtag_to_media->page_info)) {
                        $cursor = $json->data->hashtag->edge_hashtag_to_media->page_info->end_cursor;
                        if (count($json->data->hashtag->edge_hashtag_to_media->edges) == 0) {
                            //echo '<pre>'.json_encode($json, JSON_PRETTY_PRINT).'</pre>';
                            //var_dump($json);
                            //                        var_dump($curl_str);
                            if (!$without_log)
                                echo ("<br>\n No nodes!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                            $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                            $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                            if (!$without_log)
                                echo ("<br>\n Set end cursor to NULL!!!!!!!! Deleted daily work!!!!!!!!!!!!");
                        }
                    }
                }/* else if (isset($json->data) && $json->data->location == NULL) {
                  //var_dump($output);
                  print_r($curl_str);
                  $this->DB->update_reference_cursor($this->daily_work->reference_id, NULL);
                  $result = $this->DB->delete_daily_work($this->daily_work->reference_id);
                  echo ("<br>\n Set end cursor to NULL!!!!!!!! Deleted daily work!!!!!!!!!!!!");
                  } */ else {
                    if (!$without_log)
                        var_dump($output);
                    if (!$without_log)
                        print_r($curl_str);
                    if (!$without_log)
                        echo ("<br>\n Untrated error!!!");
                }
                return $json;
            } catch (\Exception $exc) {
                if (!$without_log)
                    echo $exc->getTraceAsString();
            }
        }

        public function make_curl_followers_query($query, $variables, $login_data = NULL, $proxy = "") {

            $variables = urlencode($variables);
            $url = "https://www.instagram.com/graphql/query/?query_hash=$query&variables=$variables";
            $curl_str = "curl $proxy '$url' ";
            if ($login_data !== NULL) {
                if ($login_data->mid == NULL || $login_data->csrftoken == NULL || $login_data->sessionid == NULL ||
                        $login_data->ds_user_id == NULL)
                    return NULL;
                $curl_str .= "-H 'Cookie: mid=$login_data->mid; sessionid=$login_data->sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$login_data->csrftoken; ds_user_id=$login_data->ds_user_id' ";
                $curl_str .= "-H 'X-CSRFToken: $login_data->csrftoken' ";
            }

            $cnf = new \follows\cls\system_config();
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: $cnf->CURL_USER_AGENT' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        public function make_curl_followers_str($url, $login_data, $user, $N, $cursor = NULL, $proxy = "") {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            $url .= "?query_id=17851374694183129&id=$user&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_geomedia_str($url, $login_data, $location, $N, $cursor = NULL, $proxy = "") {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            $url .= "?query_id=17881432870018455&id=$location&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/explore/locations/$location/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_tagmedia_str($url, $login_data, $tag, $N, $cursor = NULL, $proxy = "") {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            //$csrftoken = $login_data->csrftoken;
            //$ds_user_id = $login_data->ds_user_id;
            //$sessionid = $login_data->sessionid;
            //$mid = $login_data->mid;
            //if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
            //      ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
            //return NULL;
            $url .= "&first=$N";
            if ($cursor) {
                $url .= "&after=$cursor";
            }
            $curl_str = "curl $proxy '$url' ";
            //$curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            //$curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-ajax: 1' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer:  https://www.instagram.com/explore/tags/$tag/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        public function make_curl_chaining_str($url, $login_data, $user, $N, $cursor = NULL, $proxy = "") {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            //$url .= "?query_id=17880160963012870&id=$ds_user_id&first=$N";


            $url = "bd0d6d184eefd4d0ce7036c11ae58ed9";
            $variables = "{\"id\":\"$user\",\"first\":$N";
            if($cursor != NULL && $cursor != "NULL")
            {
                $variables .= ",\"after\":\"$cursor\"";
            }
            $variables .= "}";


            $curl_str = $this->make_curl_followers_query($url, $variables, $login_data, $proxy);

//            $curl_str = "curl '$url' ";
//            //$curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
//            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
//            //$curl_str .= "-H 'Origin: https://www.instagram.com' ";
//            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
//           $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
//            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
//            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
//            //$curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
//            $curl_str .= "-H 'X-Instagram-ajax: 1' ";
//            //$curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
//            $curl_str .= "-H 'Accept: */*' ";
//            $curl_str .= "-H 'Referer: https://www.instagram.com' ";
//            $curl_str .= "-H 'Authority: www.instagram.com' ";
//            if ($cursor === NULL || $cursor === '') {
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B+media.first($N)+%7B%0A++count%2C%0A++nodes+%7B%0A++++__typename%2C%0A++++caption%2C%0A++++code%2C%0A++++comments+%7B%0A++++++count%0A++++%7D%2C%0A++++comments_disabled%2C%0A++++date%2C%0A++++dimensions+%7B%0A++++++height%2C%0A++++++width%0A++++%7D%2C%0A++++display_src%2C%0A++++id%2C%0A++++is_video%2C%0A++++likes+%7B%0A++++++count%0A++++%7D%2C%0A++++owner+%7B%0A++++++id%0A++++%7D%2C%0A++++thumbnail_src%2C%0A++++video_views%0A++%7D%2C%0A++page_info%0A%7D%0A+%7D' ";
//            }
//            else {
////                $curl_str .= "--data "
////                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list&query_id=17851938028087704' ";
//                $curl_str .= "--data "
//                        . "'q=ig_user($user)+%7B%0A++followed_by.after($cursor, $N)+%7B%0A++++count%2C%0A++++page_info+%7B%0A++++++end_cursor%2C%0A++++++has_next_page%0A++++%7D%2C%0A++++nodes+%7B%0A++++++id%2C%0A++++++is_verified%2C%0A++++++followed_by_viewer%2C%0A+requested_by_viewer%2C%0A++++++full_name%2C%0A+++profile_pic_url%2C%0A++++++username%0A++++%7D%0A++%7D%0A%7D%0A&ref=relationships%3A%3Afollow_list' ";
////                "page_info": {"has_previous_page": true, "start_cursor": "AQCofdJPzGRljplmFndRzUK17kcV3cD2clwRHYSHInAWcmxn5fhtZVGZyHs1pLUafOMOw8SYZnM4UB-4WO8vM9oTjdAuvL14DmH87kZDJE2kmaW_sA-K6_yqP6pzsyC-6RE", "end_cursor": "AQDsGU9PY7SKUFVzb4g-9hUAqmN3AVn7WKa38BTEayApyPavBw6RqRriVD46_LamE1GllxTSdsFsbD3IQ7C5aEx2n7rRIaIegPoTWxPZg34SWMwLxJfI5I6ivcZ0wOZg7a4", "has_next_page": true}
//            }
//            $curl_str .= "--compressed ";
            return $curl_str;
//            }
        }

        public function make_curl_follows_str($url, $login_data, $user, $N, $cursor = NULL, $proxy = "") {
//            if (isset($login_data->csrftoken) && isset($login_data->ds_user_id) && isset($login_data->ds_user_id) && isset($login_data->sessionid)) {
            //curl 'https://www.instagram.com/graphql/query/?query_hash=c56ee0ae1f89cdbd1c89e2bc6b8f3d18&variables=%7B%22id%22%3A%223445996566%22%2C%22include_reel%22%3Afalse%2C%22first%22%3A24%7D' -H 'cookie: mid=W1ZcJgAEAAFqS5yqkDU8yMWgOgsB; mcd=3; fbm_124024574287414=base_domain=.instagram.com; csrftoken=SD9oi7sneeUpCNDWh8x6BaKyAlHjo8My; shbid=5316; ds_user_id=3445996566; sessionid=IGSC51b8d21d9492734bada23625bc750ed5bea646cf27323c3aa8094eae6ffd9cd3%3A4Qk1hcBmMMSIvYBPGp0tFxY9mKNKbVE6%3A%7B%22_auth_user_id%22%3A3445996566%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_hash%22%3A%22%22%2C%22_platform%22%3A4%2C%22_token_ver%22%3A2%2C%22_token%22%3A%223445996566%3AeLTe7DBRuhFW8cZV4sT83lermXh2YfEQ%3Ac48ded9b663f77d54b82783759eaf94d85863e58726f16f70bb718766eb2f736%22%2C%22last_refreshed%22%3A1534176134.0764064789%7D; rur=FRC; fbsr_124024574287414=r7jOj5xse5OwRR2-cCWJzU2mv3GPHVMurlwCib4bWto.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUURQNjNtdFJBVkZxSlViRUR0RERyY3VncGtMZHdzbXhVOVQ5S0FxekxLcWNaSWp5eXVHZUYtbTBZbVRDWUxfZ1ZmUzEyb0tINVBGY25LVEo5cnpIVDNkUzFRS3ByRmJrb3NxS0xVZDlEb0JrdW90Sk5nUjk3eWFWby1Fd25PbVlKeGNYeTh5clJTTjFRbzVYQkprZkY1dDAtVWtHenJpMGk4RmdSbVMzdENlV2ZiOWJiS3BIcHU0Mkkzdjl6eG9TT3NSbHNDR1owVm9qclc4S1dNcDJXZG5sZF9ZQ1BMTUNNYzhRQzhnNEpfaWJOYVNiaV8wLW1fOVdaM1NQY3dEZ3F2UDIwdDVFdWNlRUxwQUQ5T3g0VUczbW9IRmRiOUtNOU5jZ2tTMm56WmtleW5wNi1Wd2lFQUcyTkhCMDJzZXhMTDdvOHFVY3NkdkJYNGhHLW13RUdPbXZ2N0lOMzVlYm96V3hta1VHUm52d2xRYkFNY2FJX0NtUTFBRzBWWlF6ajAiLCJpc3N1ZWRfYXQiOjE1MzQxNzYxMzUsInVzZXJfaWQiOiIxMDAwMDA3MTc3NjY5MDUifQ; shbts=1534176145.3237412; urlgen="{\"time\": 1534172077}:1fpFIz:aTMWQjLq8-TN9-XxDNWznhhCpJg"' -H 'accept-encoding: gzip, deflate, br' -H 'accept-language: es-ES,es;q=0.9,en;q=0.8' -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.140 Safari/537.36' -H 'accept: */*' -H 'referer: https://www.instagram.com/riveauxmerino/following/' -H 'authority: www.instagram.com' -H 'x-requested-with: XMLHttpRequest' -H 'x-instagram-gis: 3388316f4c604e999b98d6a094c2623e' --compressed
            $csrftoken = $login_data->csrftoken;
            $ds_user_id = $login_data->ds_user_id;
            $sessionid = $login_data->sessionid;
            $mid = $login_data->mid;
            if (($csrftoken === NULL || $csrftoken === "") && ($ds_user_id === NULL || $ds_user_id === "") &&
                    ($sessionid === NULL || $sessionid === "") && ($mid === NULL || $mid === ""))
                return NULL;
            $url .= "?query_hash=c56ee0ae1f89cdbd1c89e2bc6b8f3d18&variables=";
            $variables = "{\"id\":\"$ds_user_id\",\"include_reel\":false,\"first\":$N";
            if($cursor != NULL && $cursor != "NULL")
            {
                $variables .= ",\"after\":\"$cursor\"";
            }
            $variables .= "}"; 
            $url .= urlencode($variables);
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "-H 'Origin: https://www.instagram.com' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-with: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'content-type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "--compressed ";
            return $curl_str;
        }

        public function make_insta_login($cookies) {
            $curl_str = $this->make_curl_login_str('https://www.instagram.com/accounts/login/ajax/', $cookies, "albertoreyesd84", "alberto");
            //    $curl_str = make_curl_str('https://www.instagram.com/accounts/login/ajax/', $webdriver->getAllCookies(), "josergm86", "joseramon");
            //    print("<br><br>$curl_str<br><br>");
            exec($curl_str, $output, $status);
            print_r($output);
            print_r($status);
            print_r("-> $status<br>\n<br>\n");
            return $output;
        }

        public function make_curl_login_str($url, $cookies, $user, $pass) {
            $csrftoken = $this->obtine_cookie_value($cookies, "csrftoken");
            $curl_str = "curl  '$url' ";
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Accept-Language: en-US,en;q=0.5' ";
            $curl_str .= "--compressed ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/accounts/login/' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-AJAX: 1' ";
            $curl_str .= "-H 'Content-Type: application/x-www-form-urlencoded' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Cookie: csrftoken=$csrftoken; ig_pr=1; ig_vw=1280' ";
            $curl_str .= "-H 'Connection: keep-alive' ";
            $curl_str .= "--data 'username=$user&password=$pass'";
            return $curl_str;
        }

        public function obtine_cookie_value($cookies, $name) {
            foreach ($cookies as $key => $object) {
                //print_r($object + "<br>");
                if ($object->name == $name) {
                    return $object->value;
                }
            }
            return null;
        }

        public function make_post($url) {
            $session = curl_init();
            //$headers['Accept-Encoding'] = 'gzip, deflate, br';
            //$headers['Accept-Language'] = 'en-US,en;q=0.5';
            //$headers['Content-Length'] = '37';
            $headers['Accept'] = '*/*';
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $headers['Cookie'] = "mid=V9xV2wAEAAGOzlo31h2_pyy1Huj5; sessionid=IGSC08fa8c584f5ca30ce171f701e318e8285610c3c1ffdac0a54ef0c4e43a6ec770%3Ad2m0Jq7dBiCBuUFEReJX6Pdg8TjmSKf4%3A%7B%22_token_ver%22%3A2%2C%22_auth_user_id%22%3A3858629065%2C%22_token%22%3A%223858629065%3Abz609jmb069TVeABWNYqpPxnNdWV0bxV%3Afd4a372b9561ade868a2eb39cc98f468da9c2053f34179d724c89ac52630e64c%22%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22last_refreshed%22%3A1474057695.099257%2C%22_platform%22%3A4%2C%22_auth_user_hash%22%3A%22%22%7D; csrftoken=bewWHKLF2xJq01xo98Aze2fOEnAcyiaX";
            $headers['Origin'] = "https://www.instagram.com/";
            $headers['Accept-Encoding'] = "gzip, deflate";
            $headers['Accept-Language'] = "en-US,en; q=0.8";
            $headers['User-Agent'] = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.81 Safari/537.36';
            $headers['X-Requested-With'] = 'XMLHttpRequest';
            $headers['X-CSRFToken'] = 'bewWHKLF2xJq01xo98Aze2fOEnAcyiaX';
            $headers['X-Instagram-AJAX'] = '1';
            $headers['Content-Type'] = 'application/x-www-form-urlencoded';
            $headers['Referer'] = 'https://www.instagram.com/';
            $headers['Authority'] = 'www.instagram.com';
            $headers['Content-Length'] = '0';
            $headers['Connection'] = 'keep-alive';
            curl_setopt($session, CURLOPT_URL, $url);
            curl_setopt($session, CURLOPT_POST, TRUE);
            curl_setopt($session, CURLOPT_ENCODING, "gzip");
            curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($session, CURLOPT_HEADER, 1);
            curl_setopt($session, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($session, CURLOPT_FOLLOWLOCATION, 1);
            //curl_setopt($session, CURLOPT_POSTFIELDS, $data);
            $response = curl_exec($session);
            print_r($response);
            curl_close($session);
            echo "data posted....! <br>\n";
        }

        public function get_insta_csrftoken($ch) {
            curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/");
//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($ch, CURLOPT_CAINFO, "curl-ca-bundle.crt");
            //curl_setopt ($ch, CURLOPT_CAINFO,"cacert.pem");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLINFO_COOKIELIST, true);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $response = curl_exec($ch);
            $csrftoken = $this->get_cookies_value("csrftoken");
            //var_dump($cookies);
            return $csrftoken;
        }

        public function login_insta_with_csrftoken($ch, $login, $pass, $csrftoken, $mid, $Client = NULL) {
            //$mid = $this->get_cookies_value("mid");
            //var_dump($mid);
            $pass = urlencode($pass);
            $postinfo = "username=$login&password=$pass";
            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            //            $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: en-US;en;q=0.9";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Referer: https://www.instagram.com/";
//            $headers[] = "X-CSRFToken: 77G4HebOUjsq7NZ1ChYR3sphL219KWmV";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";
//            $ip = $_SERVER['REMOTE_ADDR'];
//            if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
//                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
//                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
//            }
            /* $ip = "127.0.0.1";
              $headers[] = "REMOTE_ADDR: $ip";
              $headers[] = "HTTP_X_FORWARDED_FOR: $ip";
             */
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//            $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
//            $headers[] = "Cookie: mid=Wh8j7wAEAAFI8PVD2LfNQan_fx9D; csrftoken=77G4HebOUjsq7NZ1ChYR3sphL219KWmV; ";
//            $headers[] = "Cookie: mid=$mid; csrftoken=$csrftoken; fbsr_124024574287414=DddGyOrndRJcSIrbB8MSq8srgDYiP48BsVdMaCj9DNg.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUUFDMlo4UGVvb2Y4TDFlcEVQS09LSDNJemh5bzJOVXJjdVJEYU9zRlVYRXdYNGNzS2EtVVhZcDhRTmNWaGgtcXRJb3VqUTFDNzZmLTdFejl6bHhjUjZObDh1SG9hSzRVaE93b0JGVFdncHZzb0NjS3B0cFo5aG9teVFRSk5QSy1HSVVoU2VBVnlELUZuOFhsYnFJcFBmcndEbXNSd2VQc1dkbThwNVJoeFkyb3ltZHpPaFhDbGxVZncwMWJ6ejJiSFdDRDBIUmVPdUtEODA2NkhIRDI1ZlBfVy1YOGRaQ0dqQWVEbGZBbldUOGsxdFdDZGJYam55Vi0yTjd3NzZZTzBvdmtISk14SmZFaHlOdnU5TmJfb1BrRVQzUkt0MmM0R2h4ZGJEeVB0ZFNxNWNJcEJzbDYtVnZGRi01YnNGNTZ1RERsQWpUZU5hRUJhS1FpZVpMSUZDayIsImlzc3VlZF9hdCI6MTUxMjg1MTg0NCwidXNlcl9pZCI6IjEwMDAwOTQzMzA2OTA5NSJ9";
            $headers[] = "Cookie: mid=$mid; csrftoken=$csrftoken";
//            $url = "https://www.instagram.com/accounts/login/ajax/facebook/";
            $url = "https://www.instagram.com/accounts/login/ajax/";
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
            curl_setopt($ch, CURLOPT_NOBODY, FALSE);
//            curl_setopt($ch, CURLOPT_NOBODY, TRUE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
//            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $html = curl_exec($ch);
//            var_dump($html);
            $info = curl_getinfo($ch);
            // LOGIN WITH CURL TO TEST
            // Parse html response
            $start = strpos($html, "{");
            $json_str = substr($html, $start);
            $json_response = json_decode($json_str);
            //
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
//            var_dump($cookies);
            if (curl_errno($ch)) {
                print curl_error($ch);
            } else if (count($cookies) >= 2) {
                $login_data->csrftoken = $csrftoken;
                // Get sessionid from cookies
                $login_data->sessionid = $this->get_cookies_value("sessionid");
                // Get ds_user_id from cookies
                $login_data->ds_user_id = $this->get_cookies_value("ds_user_id");
                // Get mid from cookies
                $login_data->mid = $this->get_cookies_value("mid") ? $this->get_cookies_value("mid") : $mid;
            }
            curl_close($ch);
//            var_dump($login_data);
            return $login_data;
        }

        /**
         * Login version with exec_curl function.
         * @global array $cookies
         * @param type $ch
         * @param type $login
         * @param type $pass
         * @param type $csrftoken
         * @param type $Client
         * @return \stdClass
         */
        public function login_insta_with_csrftoken_exec($ch, $login, $pass, $csrftoken, $Client = NULL) {
            $pass = urlencode($pass);
            $postinfo = "username=$login&password=$pass";
            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            //            $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: en-US,en;q=0.5, ";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Referer: https://www.instagram.com/";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";
            $ip = $_SERVER['REMOTE_ADDR'];
            if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
                $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
                $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
            }
            $ip = "127.0.0.1";
            $headers[] = "REMOTE_ADDR: $ip";
            $headers[] = "HTTP_X_FORWARDED_FOR: $ip";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//            $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: mid=$mid; csrftoken=$csrftoken";
            $url = "https://www.instagram.com/accounts/login/ajax/";
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
            // LOGIN WITH CURL TO TEST
            // Parse html response
            $start = strpos($html, "{");
            $json_str = substr($html, $start);
            $json_response = json_decode($json_str);
            //
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
            if (curl_errno($ch)) {
                //print curl_error($ch);
            } else if (count($cookies) >= 2) {
                $login_data->csrftoken = $csrftoken;
                // Get sessionid from cookies
                $login_data->sessionid = $this->get_cookies_value("sessionid");
                // Get ds_user_id from cookies
                $login_data->ds_user_id = $this->get_cookies_value("ds_user_id");
                // Get mid from cookies
                $login_data->mid = $this->get_cookies_value("mid");
            }
            curl_close($ch);
//            var_dump($login_data);
            return $login_data;
        }

        public function get_cookies_value($key) {
            $value = NULL;
            global $cookies;
            foreach ($cookies as $index => $cookie) {
                $pos = strpos($cookie[1], $key);
                if ($pos !== FALSE) {
                    $value = explode("=", $cookie[1]);
                    if ($value[1] != "\"\"" && $value[1] != "" && $value[1] != NULL) {
                        $value = $value[1];
                        break;
                    }
                }
            }
//            array(5) (
//                [0] => array(2) (
//                    [0] => (string) Set-Cookie: target = ""
//                    [1] => (string) target = ""
//                )
//                [1] => array(2) (
//                    [0] => (string) Set-Cookie: sessionid = IGSCe1aaf9cbd92bdb97f6392541718f0f1cc3c9f104fa582781747eea41f45feab6%3AaWt6gfw3qwDWgZ4pm5z3KJdHi97IhFXj%3A%7B%22_token%22%3A%223858629065%3ASaCRKRRXkW6bOn1hABewWJMkpIjPJnVH%3A02085c8afdf6bccc4e3aeda68d33cf4d9d24fd52c778bb5ef68d9055e3de38d8%22%2C%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_token_ver%22%3A2%2C%22_platform%22%3A4%2C%22last_refreshed%22%3A1481805181.8183546%2C%22_auth_user_hash%22%3A%22%22%7D
//                    [1] => (string) sessionid = IGSCe1aaf9cbd92bdb97f6392541718f0f1cc3c9f104fa582781747eea41f45feab6%3AaWt6gfw3qwDWgZ4pm5z3KJdHi97IhFXj%3A%7B%22_token%22%3A%223858629065%3ASaCRKRRXkW6bOn1hABewWJMkpIjPJnVH%3A02085c8afdf6bccc4e3aeda68d33cf4d9d24fd52c778bb5ef68d9055e3de38d8%22%2C%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_token_ver%22%3A2%2C%22_platform%22%3A4%2C%22last_refreshed%22%3A1481805181.8183546%2C%22_auth_user_hash%22%3A%22%22%7D
//                )
//                [2] => array(2) (
//                    [0] => (string) Set-Cookie: csrftoken = LRroVq0dMCKrMf3ZEHHxlK4096vWjS4L
//                    [1] => (string) csrftoken = LRroVq0dMCKrMf3ZEHHxlK4096vWjS4L
//                )
//                [3] => array(2) (
//                    [0] => (string) Set-Cookie: ds_user_id = 3858629065
//                    [1] => (string) ds_user_id = 3858629065
//                )
//                [4] => array(2) (
//                    [0] => (string) Set-Cookie: mid = WFKNfQAEAAFmshFCfCuZHStSf0Ou
//                    [1] => (string) mid = WFKNfQAEAAFmshFCfCuZHStSf0Ou
//                )
//            )
            return $value;
        }

        public function get_insta_data($ref_prof) {
            if ($ref_prof == "" || $ref_prof == NULL) {
                throw new \Exception("This was and empty or null referece profile (ref_prof)");
            }
            $content = @file_get_contents("https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof", FALSE);
            $ch = curl_init("https://www.instagram.com/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, FALSE);
            curl_setopt($ch, CURLOPT_URL, "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            $html = curl_exec($ch);
            $string = curl_error($ch);
            $content = json_decode($html);
            curl_close($ch);
            return $content;
        }

        public function get_insta_data_from_client($ref_prof, $cookies, $proxy = NULL) {
            if ($ref_prof == "" || $ref_prof == NULL) {
                throw new \Exception("This was and empty or null referece profile (ref_prof)");
            }
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
//                                $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4";
            $headers[] = "Accept-Encoding: deflate, sdch";
            $headers[] = "Referer: https://www.instagram.com/";
            //$ip = "127.0.0.1";
            //$headers[] = "REMOTE_ADDR: $ip";
            //$headers[] = "HTTP_X_FORWARDED_FOR: $ip";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//                    $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Authority: www.instagram.com";
            if ($cookies != NULL) {
                $headers[] = "X-CSRFToken: $csrftoken";
                $headers[] = "Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id";
            }
            $url = "https://www.instagram.com/web/search/topsearch/?context=blended&query=$ref_prof";
            $ch = curl_init("https://www.instagram.com/");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

            if ($proxy != NULL) {
                //$proxy->proxy, $proxy->port, $proxy->proxy_user, $proxy->proxy_password
                //adding proxy
                curl_setopt($ch, CURLOPT_PROXY, $proxy->proxy);
                curl_setopt($ch, CURLOPT_PROXYPORT, $proxy->port);

                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                // The username and password
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, "$proxy->proxy_user:$proxy->proxy_password");
            }
            $output = curl_exec($ch);
            $string = curl_error($ch);
            curl_close($ch);
            return json_decode($output);
        }

        public function get_insta_account_edit_data_from_client($client_uname, $cookies) {
            if ($client_uname == "" || $client_uname == NULL) {
                throw new \Exception("This was and empty or null referece profile ($client_uname)");
            }
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $headers = array();
            $headers[] = "Host: www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
//                                $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4";
            $headers[] = "Accept-Encoding: deflate, sdch";
            $headers[] = "Referer: https://www.instagram.com/";
            $headers[] = "X-CSRFToken: $csrftoken";
            //$ip = "127.0.0.1";
            //$headers[] = "REMOTE_ADDR: $ip";
            //$headers[] = "HTTP_X_FORWARDED_FOR: $ip";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//                    $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Authority: www.instagram.com";
            $headers[] = "Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id";
            $url = "https://www.instagram.com/accounts/edit/";
            $ch = curl_init("https://www.instagram.com/");
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_POST, FALSE);
            $output = curl_exec($ch);
            sleep(3);
            $string = curl_error($ch);
            $output = curl_exec($ch);
            curl_close($ch);
            print $output;
            return $output;
        }

        public function get_insta_ref_prof_data($ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                $content = $this->get_insta_data($ref_prof);
                $Profile = $this->process_get_insta_ref_prof_data_for_daily_report($content, $ref_prof, $ref_prof_id);
                return $Profile;
            } catch (\Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_geolocalization_data($ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
                    $content = $this->get_insta_data($ref_prof);
                    $Profile = $this->process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id);
                }
                return $Profile;
            } catch (\Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_tag_data($ref_prof, $ref_prof_id = NULL) {
            try {
                $Profile = NULL;
                if ($ref_prof != "") {
                    $content = $this->get_insta_data($ref_prof);
                    $Profile = $this->process_get_insta_tag_data($content, $ref_prof, $ref_prof_id);
                }
                return $Profile;
            } catch (\Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_geolocalization_data_from_client($cookies, $ref_prof, $ref_prof_id = NULL, $user_id = NULL) {
            try {
                $Profile = NULL;
                //using proxy
                $proxy = NULL;
                if ($user_id != NULL) {
                    $myDB = new \follows\cls\DB();
                    $proxy = $myDB->get_client_proxy($user_id);
                }
                if ($ref_prof != "") {
                    $content = $this->get_insta_data_from_client($ref_prof, $cookies, $proxy);
                    //var_dump($content);
                    $Profile = $this->process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id);
                }
                return $Profile;
            } catch (\Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        public function get_insta_ref_prof_data_from_client($cookies, $ref_prof, $ref_prof_id, $user_id = NULL) {
            try {
                $Profile = NULL;
                $proxy = NULL;
                if ($user_id != NULL) {
                    $myDB = new \follows\cls\DB();
                    $proxy = $myDB->get_client_proxy($user_id);
                }
                if ($ref_prof != "") {
                    $content = $this->get_insta_data_from_client($ref_prof, $cookies, $proxy);
                    $Profile = $this->process_get_insta_ref_prof_data($content, $ref_prof, $ref_prof_id);
                }
                return $Profile;
            } catch (\Exception $ex) {
                return NULL;
            }
        }

        public function get_insta_tag_data_from_client($cookies, $ref_prof, $ref_prof_id = NULL, $user_id = NULL) {
            try {
                $Profile = NULL;
                //using proxy
                $proxy = NULL;
                if ($user_id != NULL) {

                    $myDB = new \follows\cls\DB();
                    $proxy = $myDB->get_client_proxy($user_id);
                }
                $content = $this->get_insta_data_from_client($ref_prof, $cookies, $proxy);
//                  $content = $this->get_insta_data_from_client($ref_prof, NULL);
//                  var_dump($content);                    
                $Profile = $this->process_get_insta_tag_data($content, $ref_prof, $ref_prof_id);

                return $Profile;
            } catch (\Exception $ex) {
                print_r($ex->message);
                return NULL;
            }
        }

        function process_get_insta_ref_prof_data($content, $ref_prof, $ref_prof_id) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $users = $content->users;
                // Get user with $ref_prof name over all matchs 
                if (is_array($users)) {
                    for ($i = 0; $i < count($users); $i++) {
                        if ($users[$i]->user->username === $ref_prof) {
                            $Profile = $users[$i]->user;
                            //var_dump($Profile);
                            $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
                            $Profile->following = $this->get_insta_ref_prof_following($ref_prof);
                            if (!isset($Profile->follower_count)) {
                                $Profile->follower_count = isset($Profile->byline) ? $this->parse_follow_count($Profile->byline) : 0;
                            }
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        function process_get_insta_ref_prof_data_for_daily_report($content, $ref_prof, $ref_prof_id) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $users = $content->users;
                // Get user with $ref_prof name over all matchs 
                if (is_array($users)) {
                    for ($i = 0; $i < count($users); $i++) {
                        if ($users[$i]->user->username === $ref_prof) {
                            $Profile = $users[$i]->user;
                            //var_dump($Profile);
//                            $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
                            $Profile->following = $this->get_insta_ref_prof_following($ref_prof);
                            if (!isset($Profile->follower_count)) {
                                $Profile->follower_count = isset($Profile->byline) ? $this->parse_follow_count($Profile->byline) : 0;
                            }
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        function process_get_insta_geolocalization_data($content, $ref_prof, $ref_prof_id) {
            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $places = $content->places;
                // Get user with $ref_prof name over all matchs 
                if (is_array($places)) {
                    for ($i = 0; $i < count($places); $i++) {
                        if ($places[$i]->place->slug === $ref_prof) {
                            $Profile = $places[$i]->place;
                            $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
//                            $Profile->following = $this->get_insta_ref_prof_following($ref_prof);
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        function process_get_insta_tag_data($content, $ref_prof, $ref_prof_id) {

            $Profile = NULL;
            if (is_object($content) && $content->status === 'ok') {
                $tags = $content->hashtags;
                // Get user with $ref_prof name over all matchs 
                if (is_array($tags)) {
                    for ($i = 0; $i < count($tags); $i++) {
                        if ($tags[$i]->hashtag->name === $ref_prof) {
                            $Profile = $tags[$i]->hashtag;
                            if ($ref_prof != NULL) {
                                $Profile->follows = $this->get_insta_ref_prof_follows($ref_prof_id);
                            }
                            break;
                        }
                    }
                }
            } else {
                //var_dump($content);
                //var_dump("null reference profile!!!");
            }
            return $Profile;
        }

        public function parse_follow_count($follow_count_str) {
            $search = " followers";
            $start = strpos($follow_count_str, $search);
            $letter = substr($follow_count_str, $start - 1, 1);
            $decimals = 1;
            $substr = substr($follow_count_str, 0, strlen($follow_count_str) - strlen($search));
            if ($letter === 'k' || $letter === 'm') { // If not integer its a 10 power
                $substr = substr($follow_count_str, 0, strlen($follow_count_str) - strlen($search) - 1);
                $decimals = $letter === 'k' ? 1000 : $decimals;
                $decimals = $letter === 'm' ? 1000000 : $decimals;
            }
            $followers = floatval($substr) * $decimals;
            return $followers;
        }

        public function get_insta_ref_prof_follows($ref_prof_id) {
            $follows = $ref_prof_id ? Reference_profile::static_get_follows($ref_prof_id) : 0;
            return $follows;
        }

        public function get_insta_ref_prof_following($ref_prof) {
            $content = @file_get_contents("https://www.instagram.com/$ref_prof/", false);
            //echo $content;
            $doc = new \DOMDocument();
//$doc->loadXML($content);
            $substr2 = NULL;
            $loaded = @$doc->loadHTML('<?xml encoding="UTF-8">' . $content);
            if ($loaded) {
                $search = "follow\":{\"count\":";
                //var_dump($doc->textContent);
                $start = strpos($doc->textContent, $search);
                $substr1 = substr($doc->textContent, $start, 100);
                $substr2 = substr($substr1, strlen($search), strpos($substr1, "}") - strlen($search));
                //var_dump($substr2);
            } else {
                //print "<br>\nProblem parsing document:<br>\n";
                //var_dump($doc);
            }
            return intval($substr2) ? intval($substr2) : 0;
        }

        public function bot_login($login, $pass, $forse = FALSE) {
            $myDB = new \follows\cls\DB();
            // Is client with cookies, we try to do some instagram action to verify the coockies are allright 
            $result = new \stdClass();
            $result->json_response = new \stdClass();
            $result->json_response->authenticated = FALSE;
            $output = array();
            $cnt = 0;
            $Client = $myDB->get_client_data_bylogin($login);
            if ($forse === FALSE || $forse == "false") {
                if (!$this->verify_cookies($Client)) {
                    $myDB->set_client_cookies($Client->id);
                    $Client->cookies = NULL;
                }
                if (isset($Client->cookies) && $Client->cookies != NULL) {
                    $cookies = json_decode($Client->cookies);
                    $csrftoken = $cookies->csrftoken;
                    $mid = $cookies->mid;
                    if ($mid !== null && $mid !== '') {
                        while (!$result->json_response->authenticated && $cnt < 2) {  // try up to 3 times, because possivel erros
                            $cnt++;
                            // Make instagram action
                            $url = "https://www.instagram.com/graphql/query/";
                            $daily_work = new \stdClass();
                            $daily_work->rp_type = 1;
                            $daily_work->cookies = $Client->cookies;
                            $daily_work->to_follow = 10;
                            $daily_work->insta_follower_cursor = NULL;
                            $daily_work->insta_name = 'cuba';
                            $daily_work->rp_insta_id = 220021938;
                            $error = NULL;
                            $page_info = 0;
                            $proxy = $this->get_proxy_str($Client);

                            //$res = $this->get_profiles_to_follow_without_log($daily_work, $error, $page_info, $proxy);
                            try {
                                $json_response = $this->get_insta_geomedia($cookies, $daily_work->rp_insta_id, $daily_work->to_follow, $daily_work->insta_follower_cursor, $proxy, true);
                                if (count($json_response->data->location->edge_location_to_media->edges) > 0) {
                                    $result->json_response->status = 'ok';
                                    $result->json_response->authenticated = TRUE;
                                }
                            } catch (\Exception $e) {
                                
                            }
                        }
                    }
                }
                // Whether cookies are ok return its
                if (isset($result->json_response->authenticated) && $result->json_response->authenticated == TRUE) {
                    $result->csrftoken = $cookies->csrftoken;
                    // Get sessionid from cookies
                    $result->sessionid = $cookies->sessionid;
                    // Get ds_user_id from cookies
                    $result->ds_user_id = $cookies->ds_user_id;
                    // Get mid from cookies
                    $result->mid = $cookies->mid;
                    return $result;
                }
            }
            // Try new API login
            try {
                $proxy = $myDB->get_client_proxy($Client->id);
                if ($proxy === NULL) {
                    $proxy_id = $GLOBALS['sistem_config']->DEFAULT_PROXY;
                    $proxy = $myDB->GetProxy($proxy_id);
                }
                $result = $this->make_login($login, $pass, $proxy->proxy, $proxy->port, $proxy->proxy_user, $proxy->proxy_password);
                $result->json_response = new \stdClass();
                $result->json_response->status = 'ok';
                $result->json_response->authenticated = TRUE;
                $myDB->set_client_cookies($Client->id, json_encode($result));

                return $result;
            } catch (\Exception $e) {
                //var_dump($e);
                // did by Jose R (si el cliente pone mal la senha por motivo X, el login va a dar una excepcion, y no le devemos cambiar las cookies, imagina que fue uno que e copio el curl a mano)
                //$myDB->set_cookies_to_null($Client->id);
                $source = 0;
                if (isset($id) && $id !== NULL && $id !== 0)
                    $source = 1;
                $myDB->InsertEventToWashdog($Client->id, $e->getMessage(), $source);
                $result->json_response->authenticated = false;
                $result->json_response->status = 'ok';

                if ((strpos($e->getMessage(), 'Challenge required') !== FALSE) || (strpos($e->getMessage(), 'Checkpoint required') !== FALSE) || (strpos($e->getMessage(), 'challenge_required') !== FALSE)) {
                    $result->json_response->message = 'checkpoint_required';
                    $result->json_response->verify_link = '/challenge/';
                } else if (strpos($e->getMessage(), 'Network: CURL error 28') !== FALSE) { // Time out by bad proxy
                    $proxy_id = ($proxy->idProxy) % 8 + 1;
                    $myDB->SetProxyToClient($Client->id, $proxy_id);
                } else if (strpos($e->getMessage(), 'password you entered is incorrect') !== FALSE)
                    $result->json_response->message = 'incorrect_password';
                else if (strpos($e->getMessage(), 'there was a problem with your request') !== FALSE)
                    $result->json_response->message = 'problem_with_your_request';
                else
                    $result->json_response->message = $e->getMessage();
                return $result;
            }
        }

        /*      public function bot_login($login, $pass, $Client = NULL)
          {
          // Is client with cookies, we try to login with str_login
          $result = new \stdClass();
          $output = array();
          if (!$Client)
          $Client = (new \follows\cls\DB())->get_client_data_bylogin($login);
          if (isset($Client->cookies) && $Client->cookies != NULL) {
          $cookies = json_decode($Client->cookies);
          $csrftoken = $cookies->csrftoken;
          $mid = $cookies->mid;
          $result->json_response = $this->str_login($mid, $csrftoken, $login, $pass);
          // TODO: Jose Angel revisar
          //                $url = "https://www.instagram.com/graphql/query/";
          //                $curl_str = $this->make_curl_followers_str("$url", $cookies, $Client->insta_id, 15);
          //print("<br><br>$curl_str<br><br>");
          //                exec($curl_str, $output, $status);
          $output = array(0);
          // TODO: Si esta en checpoint required no hacer mas nada
          //
          //
          }
          if (count($output) > 0 && isset($result->json_response->authenticated) && $result->json_response->authenticated == TRUE) {
          $result->csrftoken = $cookies->csrftoken;
          // Get sessionid from cookies
          $result->sessionid = $cookies->sessionid;
          // Get ds_user_id from cookies
          $result->ds_user_id = $cookies->ds_user_id;
          // Get mid from cookies
          $result->mid = $cookies->mid;
          return $result;
          }
          // Is not cookies or str_login return error, we make a full login
          $url = "https://www.instagram.com/";
          //            $cookie = "/home/albertord/cookies.txt";
          $login_response = false;
          $try_count = 0;
          while (!$login_response && $try_count < 2) {
          $ch = curl_init($url);
          //                $ch = curl_init();
          //                if ($login != "alberto_dreyes")
          //                    $this->csrftoken = $this->get_insta_csrftoken($ch);
          //                else
          //                    $this->csrftoken = "1HiIEyzMQMOcKhFaXWuxQd2oVkgj8L4u";
          $this->csrftoken = $this->get_insta_csrftoken($ch);
          $this->mid = $this->get_cookies_value("mid");
          if ($this->csrftoken != NULL && $this->csrftoken != "" && $this->mid) {
          $result = $this->login_insta_with_csrftoken($ch, $login, $pass, $this->csrftoken, $this->mid, $Client);
          $login_response = is_object($result->json_response);
          }
          $try_count++;
          //                if (isset($result->json_response->message) && $result->json_response->message == "checkpoint_required") {
          //                    $this->DB->set_client_status_by_login($login, user_status::VERIFY_ACCOUNT);
          //                }
          //                else if (isset($result->json_response->authenticated) && $result->json_response->authenticated == FALSE) {
          //                    $this->DB->set_client_status_by_login($login, user_status::BLOCKED_BY_INSTA);
          //                }
          //                if (!$login_response)
          //                    print "LOGIN NULL ISSUE ($login)!!! Trying $try_count of 3";
          }
          if (isset($result->json_response->authenticated) && $result->json_response->authenticated == TRUE) {
          $cookies_changed = (new \follows\cls\DB())->set_client_cookies($Client->id, json_encode($result));
          }
          //var_dump($result);
          //die("<br><br>Debug Finish!");
          return $result;
         */

        public function verify_cookies($Client) {
            if (isset($Client->cookies) && $Client->cookies != NULL) {
                $cookies = json_decode($Client->cookies);
                return (isset($cookies->csrftoken) && $cookies->csrftoken !== NULL && $cookies->csrftoken !== '' &&
                        isset($cookies->mid) && $cookies->mid !== NULL && $cookies->mid !== '' &&
                        isset($cookies->sessionid) && $cookies->sessionid !== NULL && $cookies->sessionid !== '' &&
                        isset($cookies->ds_user_id) && $cookies->ds_user_id !== NULL && $cookies->ds_user_id !== '');
            }

            return false;
        }

        public function encode_cookies($csfrtoken, $sessionid, $ds_user_id, $mid) {
            try {
                $cookies = "{\"json_response\":{\"authenticated\":true,\"user\":true,\"status\":\"ok\"},\"csrftoken\":";
                $cookies .= "\"$csfrtoken\",";
                $cookies .= "\"sessionid\":";
                if ($sessionid !== "null") {
                    $cookies .= "\"$sessionid\",";
                } else {
                    $cookies .= "null,";
                }
                $cookies .= "\"ds_user_id\":";
                $cookies .= "\"$ds_user_id\",";
                $cookies .= "\"mid\":";
                $cookies .= "\"$mid\"";
                $cookies .= "}";
                return $cookies;
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        public function make_login($login, $pass, $ip = '207.188.155.18', $port = '21316', $proxyuser = 'albertreye9917', $proxypass = '3r4rcz0b1v') {
            $instaAPI = new \follows\cls\InstaAPI();
            //TODO: capturar excepcion e dar tratamiento cuando usuario y senha no existe en IG
            try {
                $result = $instaAPI->login($login, $pass, $ip, $port, $proxyuser, $proxypass);
            } catch (\Exception $exc) {
                throw $exc;
            }
            $cookies = $result->Cookies;
            return $cookies;
        }

        public function like_fist_post($client_cookies, $client_insta_id, $Client = NULL) {

            $proxy = $this->get_proxy_str($Client);
            $result = $this->get_insta_chaining($client_cookies, $client_insta_id, 1, NULL, $proxy);
            //print_r($result);
            if ($result != NULL && is_array($result) && array_key_exists('0', $result))
            {
                $result = $this->make_insta_friendships_command($client_cookies, $result[0]->node->id, 'like', 'web/likes', $Client);
                return $result;
            }
        }

//  end of member function bot_login
// get cookie
// multi-cookie variant contributed by @Combuster in comments
        function curlResponseHeaderCallback($ch, $headerLine) {
            global $cookies;
            if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $cookie) == 1)
                $cookies[] = $cookie;
//        $cookies[] = $headerLine;
            return strlen($headerLine); // Needed by curl
        }

        public function get_reference_user($cookies, $reference_user_name) {
            //echo " -------Obtindo dados de perfil de referencia------------<br>\n<br>\n";
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $url = "https://www.instagram.com/$reference_user_name/?__a=1";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            $result = exec($curl_str, $output, $status);
            return json_decode($output[0]);
        }

        public function get_geo_post_user_info($cookies, $location_id, $post_reference, $proxy = "") {
            //echo " -------Obtindo dados de perfil que postou na geolocalizacao------------<br>\n<br>\n";
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $url = "https://www.instagram.com/p/$post_reference/?taken-at=$location_id&__a=1";
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            $result = exec($curl_str, $output, $status);
            $object = json_decode($output[0]);
            if (is_object($object) && isset($object->graphql->shortcode_media->owner)) {
                return $object->graphql->shortcode_media->owner;
            }
            return NULL;
        }

        public function get_tag_post_user_info($cookies, $post_reference, $proxy = "") {
            //echo " -------Obtindo dados de perfil que postou na geolocalizacao------------<br>\n<br>\n";
            $csrftoken = isset($cookies->csrftoken) ? $cookies->csrftoken : 0;
            $ds_user_id = isset($cookies->ds_user_id) ? $cookies->ds_user_id : 0;
            $sessionid = isset($cookies->sessionid) ? $cookies->sessionid : 0;
            $mid = isset($cookies->mid) ? $cookies->mid : 0;
            $url = "https://www.instagram.com/p/$post_reference/?__a=1";
            $curl_str = "curl $proxy '$url' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'Accept-Language: pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'Cookie: mid=$mid; sessionid=$sessionid; s_network=; ig_pr=1; ig_vw=1855; csrftoken=$csrftoken; ds_user_id=$ds_user_id' ";
            $curl_str .= "--compressed ";
            $result = exec($curl_str, $output, $status);
            $object = json_decode($output[0]);
            if (is_object($object) && isset($object->graphql->shortcode_media->owner)) {
                return $object->graphql->shortcode_media->owner;
            }
            return NULL;
        }

        public function str_login($mid, $csrftoken, $user, $pass) {
            $url = "https://www.instagram.com/accounts/login/ajax/";
//            $url = "https://www.instagram.com/accounts/login/ajax/facebook/";
            $curl_str = "curl '$url' ";
            $curl_str .= "-H 'Accept: */*' ";
            $curl_str .= "-H 'Accept-Encoding: gzip, deflate, br' ";
            $curl_str .= "-H 'Accept-Language: en-US;en;q=0.5' ";
//            $curl_str .= "-H 'Cookie: mid=$mid; csrftoken=$csrftoken; fbsr_124024574287414=DddGyOrndRJcSIrbB8MSq8srgDYiP48BsVdMaCj9DNg.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUUFDMlo4UGVvb2Y4TDFlcEVQS09LSDNJemh5bzJOVXJjdVJEYU9zRlVYRXdYNGNzS2EtVVhZcDhRTmNWaGgtcXRJb3VqUTFDNzZmLTdFejl6bHhjUjZObDh1SG9hSzRVaE93b0JGVFdncHZzb0NjS3B0cFo5aG9teVFRSk5QSy1HSVVoU2VBVnlELUZuOFhsYnFJcFBmcndEbXNSd2VQc1dkbThwNVJoeFkyb3ltZHpPaFhDbGxVZncwMWJ6ejJiSFdDRDBIUmVPdUtEODA2NkhIRDI1ZlBfVy1YOGRaQ0dqQWVEbGZBbldUOGsxdFdDZGJYam55Vi0yTjd3NzZZTzBvdmtISk14SmZFaHlOdnU5TmJfb1BrRVQzUkt0MmM0R2h4ZGJEeVB0ZFNxNWNJcEJzbDYtVnZGRi01YnNGNTZ1RERsQWpUZU5hRUJhS1FpZVpMSUZDayIsImlzc3VlZF9hdCI6MTUxMjg1MTg0NCwidXNlcl9pZCI6IjEwMDAwOTQzMzA2OTA5NSJ9' ";
            $curl_str .= "-H 'Cookie: mid=$mid; csrftoken=$csrftoken' ";
            $curl_str .= "-H 'Host: www.instagram.com' ";
            $curl_str .= "-H 'Referer: https://www.instagram.com/' ";
            $curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0' ";
            $curl_str .= "-H 'X-Requested-With: XMLHttpRequest' ";
            $curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            $curl_str .= "-H 'X-Instagram-AJAX: 1' ";
            $curl_str .= "-H 'Authority: www.instagram.com' ";
            $curl_str .= "-H 'REMOTE_ADDR: 127.0.0.1' -H 'HTTP_X_FORWARDED_FOR: 127.0.0.1'";
            $curl_str .= " --data 'username=$user&password=$pass' ";
            exec($curl_str, $output, $status);
            return json_decode($output[0]);
        }

        public function follow_me_myself($login_data, $prof_id = '3916799608') {
            $result = NULL;
            if ($login_data) {
                $result = $this->make_insta_friendships_command($login_data, $prof_id, 'follow');
                $prof_id = '4542814483'; // DUMBU HELP
                $result = $this->make_insta_friendships_command($login_data, $prof_id, 'follow');
                //$dumbusuport_prof_id = '4454382603';
                //$result = $this->make_insta_friendships_command($login_data, $dumbusuport_prof_id, 'follow');
            }
            return $result;
        }

        public function checkpoint_requested($login, $pass, $Client = NULL) {
            try {
                $DB = new \follows\cls\DB();
                $instaAPI = new \follows\cls\InstaAPI();
                $Client = $DB->get_client_data_bylogin($login);
                $Proxy = $DB->get_client_proxy($Client->id);
                if ($Proxy == NULL)
                    $Proxy = $DB->GetProxy(8);
                $result2 = $instaAPI->login($login, $pass, $Proxy->proxy, $Proxy->port, $Proxy->proxy_user, $Proxy->proxy_password);
                return $result2;
            } catch (\InstagramAPI\Exception\ChallengeRequiredException $exc) {
                $res = $exc->getResponse()->getChallenge()->getApiPath();
                $response = $this->get_challenge_data($res, $login, $Client);
                if (isset($response->challenge->challengeType) && ($response->challenge->challengeType == "SelectVerificationMethodForm")) {
                    $response = $this->get_challenge_data($res, $login, $Client, 0);
                }
                return $response;
            }
        }

        function get_challenge_data($challenge, $login, $Client, $choice = 1) {
            //(new \follows\cls\Client())->set_client_cookies($Client->id, NULL);
            if (!$Client)
                $Client = (new \follows\cls\DB())->get_client_data_bylogin($login);
            $url = $ch = curl_init("https://www.instagram.com/");
            $csrftoken = $this->get_insta_csrftoken($ch);
            $urlgen = $this->get_cookies_value('urlgen');
            $mid = $this->get_cookies_value('mid');
            $rur = $this->get_cookies_value('rur');
            $ig_vw = $this->get_cookies_value('ig_vw');
            $ig_pr = $this->get_cookies_value('ig_pr');
            $ig_vh = $this->get_cookies_value('ig_vh');
            $ig_or = $this->get_cookies_value('ig_or');

            $url = "https://www.instagram.com";
            $url .= $challenge;

            $cookies = "{\"csrftoken\":\"$csrftoken\","
                    . "\"mid\":\"$mid\", \"checkpoint_url\": \"$challenge\" }";
            (new \follows\cls\Client())->set_client_cookies($Client->id, $cookies);

            $headers[] = "Origin: https://www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0' -H 'Accept: */*";
            $headers[] = "Accept-Language: en-US,en;q=0.5";
            $headers[] = "Referer: $url";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: csrftoken=$csrftoken; mid=$mid; rur=$rur; ig_vw=$ig_vw; ig_pr=$ig_pr; ig_vh=$ig_vh; ig_or=$ig_or";
            $headers[] = "Connection: keep-alive";
            $postinfo = "choice=$choice";

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            //curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));

            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
            $start = strpos($html, "{");
            $json_str = substr($html, $start);
            //exec($curl_str, $output, $status);
            // var_dump($output);
            $resposta = json_decode($json_str);
            //var_dump($output);
            //$this->temporal_log($curl_str);
            (new \follows\cls\DB())->InsertEventToWashdog($Client->id, "Code Requested");
            return $resposta;
        }

        public function make_checkpoint($login, $code) {
            $Client = (new \follows\cls\DB())->get_client_data_bylogin($login);
            $cookies = json_decode($Client->cookies);
            $csrftoken = $cookies->csrftoken;
            $mid = $cookies->mid;
            $url = "https://www.instagram.com" . $cookies->checkpoint_url;
            //$curl_str = "curl '$url' ";
            //$curl_str .= "-H 'origin: https://www.instagram.com' ";
            //$curl_str .= "-H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64; rv:50.0) Gecko/20100101 Firefox/50.0' -H 'Accept: */*' ";
            //$curl_str .= "-H 'Accept-Language: en-US,en;q=0.5' --compressed ";
            //$curl_str .= "-H 'Referer: $url' ";
            //$curl_str .= "-H 'X-CSRFToken: $csrftoken' ";
            //$curl_str .= "-H 'X-Instagram-AJAX: 1' -H 'Content-Type: application/x-www-form-urlencoded' -H 'X-Requested-With: XMLHttpRequest' ";
            //$curl_str .= "-H 'Cookie: csrftoken=$csrftoken; ";
            //$curl_str .= "mid=$mid; ";
            //$curl_str .= "rur=$rur; ig_vw=$ig_vw; ig_pr=$ig_pr; ig_vh=$ig_vh; ig_or=$ig_or' ";
            //$curl_str .= "-H 'Connection: keep-alive' --data 'security_code=$code' --compressed";
            $ch = curl_init("https://www.instagram.com");
            $headers = array();

            $postinfo = "security_code=$code";
            $headers[] = "Origin: https://www.instagram.com";
            $headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:49.0) Gecko/20100101 Firefox/49.0";
            //            $headers[] = "Accept: application/json";
            $headers[] = "Accept: */*";
            $headers[] = "Accept-Language: en-US,en;q=0.5, ";
            $headers[] = "Accept-Encoding: gzip, deflate, br";
            $headers[] = "Referer: $url";
            $headers[] = "X-CSRFToken: $csrftoken";
            $headers[] = "X-Instagram-AJAX: 1";

            //$index = rand(0, 4);
            //$cnt = 0;
            //$ip = $this->IPS["IPS"][$index];
            /* foreach ($this->IPS as $value) {
              $ip = $value;
              if($cnt >= $index)
              {
              break;
              }
              } */
            //if ($Client != NULL && $Client->HTTP_SERVER_VARS != NULL) { // if 
            //    $HTTP_SERVER_VARS = json_decode($Client->HTTP_SERVER_VARS);
            //    $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
            //}
            //$ip = "127.0.0.1";
            //$headers[] = "REMOTE_ADDR: $ip";
            //$headers[] = "HTTP_X_FORWARDED_FOR: $ip";
            $headers[] = "Content-Type: application/x-www-form-urlencoded";
//            $headers[] = "Content-Type: application/json";
            $headers[] = "X-Requested-With: XMLHttpRequest";
            $headers[] = "Cookie: mid=$mid; csrftoken=$csrftoken";

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($ch, CURLOPT_POST, true);
            //            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
            //            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, "curlResponseHeaderCallback"));
            global $cookies;
            $cookies = array();
            $html = curl_exec($ch);
            $info = curl_getinfo($ch);
            // LOGIN WITH CURL TO TEST
            // Parse html response
            $start = strpos($html, "200") != 0;
            $json_str = substr($html, $start);
            $json_response = json_decode($json_str);
            //
            $login_data = new \stdClass();
            $login_data->json_response = $json_response;
            if (count($cookies) >= 2 && $start) {

                $login_data->json_response = json_decode('{"authenticated":true,"user":true,"status":"ok"}');

                $login_data->csrftoken = $this->get_cookies_value("csrftoken");
                // Get sessionid from cookies

                $login_data->sessionid = $this->get_cookies_value("sessionid");
                // Get ds_user_id from cookies
                $login_data->ds_user_id = $this->get_cookies_value("ds_user_id");

                // Get mid from cookies
                $login_data->mid = $this->get_cookies_value("mid");
                if ($login_data->mid == NULL || $login_data->mid == "") {
                    $login_data->mid = $mid;
                }
                (new \follows\cls\Client())->set_client_cookies($Client->id, json_encode($login_data));
            } else {
                $login_data->json_response = json_decode('{"authenticated":false, "status":"fail"}');
            }

            curl_close($ch);

            /* exec($curl_str, $output, $status);     
              $res = json_decode($output[0]);
              if($res.status === "ok")
              {
              (new \follows\cls\Client())->set_client_cookies($Client->id);
              } */

            (new \follows\cls\DB())->InsertEventToWashdog($Client->id, json_encode($login_data));
            return $login_data;
        }

        public function set_client_cookies_by_curl($client_id, $curl, $robot_id = NULL) {
            try {
                $myDB = new \follows\cls\DB();
                //curl 'https://www.instagram.com/accounts/login/ajax/' -H 'cookie: mid=Wh8j7wAEAAFI8PVD2LfNQan_fx9D; ig_or=portrait-primary; ig_vw=423; ig_pr=2; ig_vh=591; fbm_124024574287414=base_domain=.instagram.com; fbsr_124024574287414=QUaWW1MeWiEGTHDLVO2tm1aym96hpJFOTfvK8VjdAwk.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImNvZGUiOiJBUUQ5MFZhTVdBeEtPakZFTFFzTFlKZW9LV1prVmNldFB4TnhYVnBkSmprdU9GMjg5TlFDM3RIZGVabFQ3OFpQOVk0T0NORVZyTHZkX0hLYjIwNDFuNWF5UlJWdDFlLWVoTW81UEpuR0c3bjFlSF83VnpJdXZDb0gzZDNZX1hWbWtfbmVZSV9qSlhGLTNLZFpScmlxc1ctb1pfWVo5QkEyYWFjRHdqNE03YzNJTl9rLTB0SGVkT3l1VVl0d0xaY0VDMjFHOG1sWUdDRTFVQUlpSzRKVUNHSllsVmdSMzBhSS1jV1h5QURRUk5VY2RfYTREQWwweWRtYlBmUDBoSkhxRzJLc2o2d0FoekJrMnhqRHQ3cm5XX0FtempQQ200NWZMUC1BV1RLYlJIblpKWjRsT0h5Y3RnaU9PNDZqSXlUYlVucnkzR0dxTXhCcG1VZWtjc1BNVGllak5DQzRLVW9saWtHcU81RDBsaERfS1FkZWgwNjJiVHNGcDR5dlpjbWJ1MmMiLCJpc3N1ZWRfYXQiOjE1MTMwNDkwNjQsInVzZXJfaWQiOiIxMDAwMDA3MTc3NjY5MDUifQ; csrftoken=3XfKEa81tbNOorjQuO4s1kAowNXYv5fG; rur=FTW; urlgen="{\"time\": 1513018251\054 \"200.20.15.39\": 2715}:1eObBA:XQDYQSuMd6OrRm_G9jZL11t_UsI"' -H 'origin: https://www.instagram.com' -H 'accept-encoding: gzip, deflate, br' -H 'accept-language: en-US,en;q=0.8' -H 'user-agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Mobile Safari/537.36' -H 'x-requested-with: XMLHttpRequest' -H 'x-csrftoken: 3XfKEa81tbNOorjQuO4s1kAowNXYv5fG' -H 'x-instagram-ajax: 1' -H 'content-type: application/x-www-form-urlencoded' -H 'accept: */*' -H 'referer: https://www.instagram.com/' -H 'authority: www.instagram.com' --data 'username=riveauxmerino&password=Notredame88' --compressed
                $myDB->save_curl($client_id, $curl);
                $csrftoken = "";
                if (preg_match('/csrftoken=(\w+)/mi', $curl, $match) == 1) {
                    $csrftoken = "$match[1]";
                }
                $mid = "";
                if (preg_match('/mid=([^;"\' ]+)/mi', $curl, $match) == 1) {
                    $mid = "$match[1]";
                }
                $sessionid = "";
                if (preg_match('/sessionid=([^;"\' ]+)/mi', $curl, $match) == 1) {
                    $sessionid = "$match[1]";
                }
                $ds_user_id = "";
                if (preg_match('/ds_user_id=([^;"\' ]+)/mi', $curl, $match) == 1) {
                    $ds_user_id = "$match[1]";
                }
                $password = NULL;
                if (preg_match('/password=([^;"\' ]+)/mi', $curl, $match) == 1) {
                    $password = $match[1];
                } else {
                    $password = NULL;
                }
                if ($ds_user_id == "") {
                    $obj = $myDB->get_client_instaid_data($client_id);
                    $ds_user_id = "$obj->insta_id";
                }
                if ($sessionid === 'null' || $sessionid === "") {
                    $url = "https://www.instagram.com/";
                    $Client = (new \follows\cls\DB())->get_client_data($client_id);
                    $ch = curl_init($url);
                    $result = $this->login_insta_with_csrftoken($ch, $Client->login, $password, $csrftoken, $mid);
                    $result->json_response = new \stdClass();
                    $result->json_response->authenticated = true;
                    $result->json_response->user = true;
                    $result->json_response->status = "ok";
                    $cookies = json_encode($result);
                } else {
                    $cookies = "{\"json_response\":{\"authenticated\":true,\"user\":true,\"status\":\"ok\"},\"csrftoken\":";
                    $cookies .= "\"$csrftoken\",";
                    $cookies .= "\"sessionid\":";
                    $cookies .= "\"$sessionid\",";
                    $cookies .= "\"ds_user_id\":";
                    $cookies .= "\"$ds_user_id\",";
                    $cookies .= "\"mid\":";
                    $cookies .= "\"$mid\"";
                    $cookies .= "}";
                }
                if ($password !== null) {
                    $res = $myDB->SetPasword($client_id, $password);
                }


                $res = $myDB->set_client_cookies($client_id, $cookies) && $res;
                $myDB->InsertEventToWashdog($client_id, "SET CURL");
                $myDB->InsertEventToWashdog($client_id, $curl);
            } catch (\Exception $exc) {
                echo $exc->getTraceAsString();
            }
        }

        function temporal_log($data) {
            $my_file = '/var/log/dumbu.txt';
            try {
                $handle = fopen($my_file, 'a+');
                fwrite($handle, "\n\n");
                fwrite($handle, $data);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }

        /*
          public function clean_cursors()
          {
          $clients = (new \follows\cls\Client())->get_clients();
          $DB = new \follows\cls\DB();
          foreach ($clients as $client)
          {
          if($this->verify_cookies($client) && $client->status_id == user_status::ACTIVE)
          {
          $cookies = json_decode($client->cookies);
          $references = $DB->get_reference_profiles_with_problem($client->id);
          while ($reference = $references->fetch_object()) {
          if($reference->type == 0)
          {
          $data = $this->get_insta_ref_prof_data_from_client($cookies,$reference->insta_id);
          $follower =  $user_data->follower_count;
          if($reference->follows/ $follower < 0.25)
          {
          $DB->reset_referecne_prof($reference_id);
          }

          }
          else if($reference->type == 1)
          {
          $data = $this->get_insta_geolocalization_data_from_client($cookies,  $reference->insta_id);
          //$follower =  $user_data->follower_count;
          /*if($refenrence->follows/ $follower < 0.25)
          {
          $DB->reset_referecne_prof($reference_id);
          }
          }
          else if($reference->type == 2)
          {
          $data = $this->get_insta_ref_prof_data_from_client($client->cookies,$reference->insta_id);
          //$follower =  $user_data->follower_count;
          /*if($refenrence->follows/ $follower < 0.25)
          {
          $DB->reset_referecne_prof($reference_id);
          }
          }
          }
          }
          }
          }
         */

        public function get_proxy_str($Client) {
            if ($Client != NULL) {
                $myDB = new \follows\cls\DB();
                $proxy = $myDB->get_client_proxy($Client->id);
                if ($proxy === NULL) {
                    $proxy_id = $GLOBALS['sistem_config']->DEFAULT_PROXY;
                    $proxy = $myDB->GetProxy($proxy_id);
                }
                $proxy = "--proxy '$proxy->proxy_user:$proxy->proxy_password@$proxy->proxy:$proxy->port'";
                return $proxy;
            }
            return "";
        }

        public function SetUnautorizedClientStatus($client_id) {
           $this->DB->set_cookies_to_null($client_id);           
           $client = (new \follows\cls\Client())->get_client($client_id);
           $this->RecognizeClientStatus($client);
        }
        
        public function RecognizeClientStatus($client)
        {
            $result = $this->bot_login($client->login, $client->pass);
            if (isset($result->json_response->message)) {
                if ($result->json_response->message == 'checkpoint_required') {
                    $this->DB->set_client_status($client_id, user_status::VERIFY_ACCOUNT);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::ROBOT_VERIFY_ACCOUNT, 1, $this->id);
                } else if ($result->json_response->message == 'incorrect_password') {
                    $this->DB->set_client_status($client_id, user_status::BLOCKED_BY_INSTA);
                    $this->DB->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_INSTA, 1, $this->id);
                }
            }
            return $result;
        }
        
        public function process_get_followers_error($daily_work, $client, $quantity, $proxy)
        {
             $result = $this->RecognizeClientStatus($client);
             if(isset($result->json_response->authenticated) && $result->json_response->authenticated)
             {                 
                //retry of graph request
                  $json_response = $this->get_insta_followers($result, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor, $proxy);
                  if($json_response === NULL)
                  {
                      $this->DB->update_reference_cursor($daily_work->reference_id,NULL);
                      $this->DB->delete_daily_work($daily_work->reference_id);
                      $this->DB->InsertEventToWashdog($client_id, washdog_type::ERROR_IN_PR, 1, $this->id, "unexistence reference profile or may be the reference profile is block ing the client");
                    
                  }
                  else if(isset($json_response->status) && $json_response->status == 'ok')
                  {
                      return $json_response;                  
                  }
                  return NULL;              
             }  
             else if($result->json_response->message == 'checkpoint_required' || $result->json_response->message == 'incorrect_password')
             {
                 //unautorized, bloc by password or an api unrecognized error
                 $this->DB->delete_daily_work_client($daily_work->client_id);
             }
        }
    }

// end of Robot
}
?>