<?php

namespace business\worker {
  
  use business\Business; 
 
  require_once config_item('business-class');
  
     /*
    require_once '../InstaApiWeb';
    require_once '../InstaApiWeb/Exception';
    require_once '../InstaApiWeb/Response';
       
    require_once 'DB.php';
    require_once 'Gmail.php';
    require_once 'Reference_profile.php';
    require_once 'Day_client_work.php';
    require_once 'washdog_type.php';
    require_once 'system_config.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/utils.php';
    require_once 'InstaAPI.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/externals/vendor/autoload.php';
    */
  
  
   /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an Robot worker class.
   * 
   */
    
    class Robot extends Business{

        public function __construct() {
          $ci = &get_instance();
      
          $ci->load->model('db_model');
          //$ci->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');
            
        /*  $config = parse_ini_file(dirname(__FILE__) . $conf_file, true);
            $this->IPS = $config["IPS"];
            $this->Day_client_work = new Day_client_work();
            $this->Ref_profile = new Reference_profile();*/
        }

        public function do_follow_unfollow_work($Followeds_to_unfollow, DailyWork $daily_work, ErrorType &$error = NUL) {
            $ci = &get_instance();
            //$this->Day_client_work = $Day_client_work;
            //$this->Ref_profile = $Ref_profile;
            //$DB = new DB();
            //CONCERTAR follows...
            $Client = (new \follows\cls\Client())->get_client($daily_work->client_id);
            $this->daily_work = $daily_work;
            $login_data = $daily_work->login_data;
            // Unfollow same profiles quantity that we will follow
            $Profile = new Profile();

            $has_next = count($Followeds_to_unfollow);
            echo "<br>\nClient: $daily_work->client_id <br>\n";
            echo "<br>\nnRef Profil: $daily_work->insta_name<br>\n" . " Count: " . count($Followeds_to_unfollow) . " Hasnext: $has_next - ";
            echo date("Y-m-d h:i:sa");
            echo "<br>\n make_insta_friendships_command UNFOLLOW <br>\n";
            
            
            // Do unfollow work
            if(!$this->verify_cookies($Client))
            {                
                $result = $ci->db_model->delete_daily_work_client($daily_work->client_id);
                //$ci->db_model->set_client_cookies($daily_work->client_id);
                $ci->db_model->set_client_status($daily_work->client_id, user_status::BLOCKED_BY_TIME);
                $ci->db_model->insert_event_to_washdog($daily_work->client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id, "Respuesta incompleta: $curl_str");
                $error = TRUE;
                var_dump($curl_str);
                var_dump("Error in do_follow_unfollow_work!!! cookies wrong");
            }
            
            
            for ($i = 0; $error === FALSE && $i < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && ($has_next); $i++) {
                $error = FALSE;
                // Next profile to unfollow, not yet unfollwed
                $Profile = array_shift($Followeds_to_unfollow);
                $Profile->unfollowed = FALSE;
                $curl_str = "";
                $json_response = $this->make_insta_friendships_command(
                        $login_data, $Profile->followed_id, 'unfollow', 'web/friendships', $Client, $curl_str
                );
                if ($json_response === NULL) {
                    var_dump("Null response from instagram in unfullow\n");
                } else if (is_object($json_response) && $json_response->status == 'ok') { // if unfollowed 
                    $Profile->unfollowed = TRUE;
                    var_dump($json_response);
                    echo "Followed_ID: $Profile->followed_id";
                    if (isset($Profile->insta_name)) {
                        echo "($Profile->insta_name)";
                    }
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
                    } else if ($error == 9) { // To much request response string only
                        $error = FALSE;
                        break;
                    } else if ($error == 10) {
                        (new Gmail())->sendAuthenticationErrorMail($Client->name, $Client->email);
                    } else {
                        break;
                    }
                }
                array_push($Followeds_to_unfollow, $Profile);
            }

            // Do follow work
            //daily work: cookies   reference_id 	to_follow 	last_access 	id 	insta_name 	insta_id 	client_id 	insta_follower_cursor 	user_id 	credit_card_number 	credit_card_status_id 	credit_card_cvc 	credit_card_name 	pay_day 	insta_id 	insta_followers_ini 	insta_following 	id 	name 	login 	pass 	email 	telf 	role_id 	status_id 	languaje 
            $Ref_profile_follows = array();
            $follows = 0;
            echo "<br>\nmake_insta_friendships_command FOLLOW (like firsts = $daily_work->like_first): $daily_work->to_follow <br>\n";
            if ($error === FALSE && $daily_work->to_follow > 0) { // If has to follow
                $get_followers_count = 0;
                $error = FALSE;
                while ($error === FALSE && $follows < $GLOBALS['sistem_config']->REQUESTS_AT_SAME_TIME && $get_followers_count < $GLOBALS['sistem_config']->MAX_GET_FOLLOWERS_REQUESTS) {
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
                                $followed_in_db = $ci->db_model->cmd_is_profile_followed($daily_work->client_id, $Profile->id);
                                //$followed_in_db = NULL;
                                if (!$followed_in_db && !$following_me && $valid_profile) { // Si no lo he seguido en BD y no me está siguiendo
                                    // Do follow request
                                    echo "FOLLOWED <br>\n";
                                    $curl_str = "";
                                    $json_response2 = $this->make_insta_friendships_command($login_data, $Profile->id, 'follow', 'web/friendships', $Client, $curl_str);
                                    if ($json_response2 === NULL) {
                                        var_dump("NULL response from instagram in follow\n");
                                    }
                                    if (is_object($json_response2) && $json_response2->status == 'ok') { // if response is ok
                                        array_push($Ref_profile_follows, $Profile);
                                        $follows++;
                                        if ($daily_work->like_first /* && count($Profile_data->graphql->user->media->nodes) */) {
                                            //$json_response_like = $this->make_insta_friendships_command($login_data, $Profile_data->user->media->nodes[0]->id, 'like', 'web/likes');
                                            $this->like_fist_post($login_data, $Profile->id, $Client, $error);
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
                        $ci->db_model->update_reference_cursor($daily_work->reference_id, $page_info->end_cursor);
                        if (!$page_info->has_next_page)
                            break;
                    } else {
                        echo "Problem with pageinfo \n <br>";
                        var_dump($page_info);
                        break;
                    }
                }
            }
            echo "Time to process client: ".date("Y-m-d h:i:sa")."<br>\n";
            echo "<br><br>\n\n________________________________________________<br><br>\n\n";
            return $Ref_profile_follows;
        }

        public function do_follow_work (DailyWork $daily_work, ErrorType &$error = NULL)
        {
            //no está en el antiguo fichero 
        }

        public function do_unfollow_work ($Followeds_to_unfollow, DailyWork $daily_work, ErrorType &$error = NULL)
        {
            //está comentada en el antiguo fichero 
        }

        public function process_error($json_response) {
            $ci = &get_instance();
            $Profile = new Profile();
            $ref_prof_id = $this->daily_work->rp_id;
            $client_id = $this->daily_work->client_id;
            $error = $Profile->parse_profile_follow_errors($json_response);
            switch ($error) {
                case 1: // "Com base no uso anterior deste recurso, sua conta foi impedida temporariamente de executar essa ação. Esse bloqueio expirará em há 23 horas."
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_INSTA!!! <br>\n";
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    $ci->db_model->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    break;
                case 2: // "Você atingiu o limite máximo de contas para seguir. É necessário deixar de seguir algumas para começar a seguir outras."
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    var_dump($result);
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::SET_TO_UNFOLLOW, 1, $this->id);
                    $ci->db_model->set_client_status($client_id, user_status::UNFOLLOW);
                    print "<br>\n Client (id: $client_id) set to UNFOLLOW!!! <br>\n";
//                    print "<br>\n Client (id: $client_id) MUST set to UNFOLLOW!!! <br>\n";
                    break;
                case 3: // "Unautorized"
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    $this->SetUnautorizedClientStatus($client_id);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_INSTA!!! <br>\n";
                    break;
                case 4: // "Parece que você estava usando este recurso de forma indevida"
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    var_dump($result);
                    $ci->db_model->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    print "<br>\n Unautorized Client (id: $client_id) set to BLOCKED_BY_TIME!!! <br>\n";
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    // Alert when insta block by IP
                    $result = $ci->db_model->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                    $rows_count = $result->num_rows;
                    if ($rows_count == 100 || $rows_count == 150 || ($rows_count >= 200 && $rows_count <= 210)) {
                        $Gmail = new Gmail();
                        $Gmail->send_client_login_error("josergm86@gmail.com", "Jose!!!!!!! BLOQUEADOS 4= " . $rows_count, "Jose");
                        $Gmail->send_client_login_error("ruslan.guerra88@gmail.com", "Ruslan!!!!!!! BLOQUEADOS 4= " . $rows_count, "Ruslan");
                    }
                    print "<br>\n BLOCKED_BY_TIME!!! number($rows_count) <br>\n";
                    break;
                case 5: // "checkpoint_required"
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    var_dump($result);
                    $ci->db_model->set_client_status($client_id, user_status::VERIFY_ACCOUNT);
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::ROBOT_VERIFY_ACCOUNT, 1, $this->id);
                    $ci->db_model->set_client_cookies($client_id, NULL);
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
                    $proxy = $ci->db_model->get_client_proxy($client_id);

                    //$new_proxy = ($proxy->idProxy + rand(0, 6)) % 8 + 1;
                    $new_proxy = ($proxy->idProxy) % 8 + 1;
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::SET_PROXY, 1, $this->id, "proxy set from proxy $proxy->idProxy to $new_proxy");

                    var_dump("Set Proxy ($proxy->idProxy) of client ($client_id) to proxy ($new_proxy)\n");
                    $ci->db_model->set_proxy_to_client($client_id, $new_proxy);

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
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
                    $ci->db_model->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    //var_dump($result);
                    print "<br>\n Esta mensagem contém conteúdo que foi bloqueado pelos nossos sistemas de segurança. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;
                case 9: // "Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde." 
                    print "<br>\n Ocorreu um erro ao processar essa solicitação. Tente novamente mais tarde. (ref_prof_id: $ref_prof_id)!!! <br>\n";
                    break;
                case 10:
                    print "<br> Empty array in POST </br>";
                    $proxy = $ci->db_model->get_client_proxy($client_id);

                    $new_proxy = ($proxy->idProxy) % 8 + 1;
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::SET_PROXY, 1, $this->id, "proxy set from proxy $proxy->idProxy to $new_proxy");

                    var_dump("Set Proxy ($proxy->idProxy) of client ($client_id) to proxy ($new_proxy)\n");
                    $ci->db_model->set_proxy_to_client($client_id, $new_proxy);
                    /*
                      $time = $GLOBALS['sistem_config']->INCREASE_CLIENT_LAST_ACCESS;
                      $ci->db_model->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id, "access incresed in $time");

                      $ci->db_model->Increase_Client_Last_Access($client_id, $GLOBALS['sistem_config']->INCREASE_CLIENT_LAST_ACCESS);

                      $result = $ci->db_model->get_clients_by_status(user_status::BLOCKED_BY_TIME);
                     */
                    break;
                case 11:
                    print "<br> se ha bloqueado. Vuelve a intentarlo</br>";
                    $result = $ci->db_model->delete_daily_work_client($client_id);
                    //$ci->db_model->set_client_cookies($client_id);                    
                    $ci->db_model->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    break;
                case 12:
                    $result = $ci->db_model->update_reference_cursor($ref_prof_id, NULL);
                    print "<br>$ref_prof_id set to null<br>\n";
                    break;
                default:
                    print "<br>\n Client (id: $client_id) not error code found ($error)!!! <br>\n";
//                    $result = $ci->db_model->delete_daily_work_client($client_id);
//                    $ci->db_model->InsertEventToWashdog($client_id, washdog_type::BLOCKED_BY_TIME, 1, $this->id);
//                    $ci->db_model->set_client_status($client_id, user_status::BLOCKED_BY_TIME);
                    $error = FALSE;
                    break;
            }
            return $error;
        }

        public function get_profiles_to_follow(DayliWork $daily_work, ErrorType &$error = NULL, &$page_info) {
            $ci = &get_instance();
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
                            $ci->db_model->update_reference_cursor($daily_work->reference_id, NULL);
                            echo ("<br>\n Updated Reference Cursor to NULL!!");
                            $result = $ci->db_model->delete_daily_work($daily_work->reference_id);
                            if ($result) {
                                echo ("<br>\n Deleted Daily work!! Ref $daily_work->reference_id");
                            }
                        } else if ($page_info->has_next_page === FALSE && $page_info->end_cursor === NULL) {
//                            $Client = new Client();
//                            $Client = $Client->get_client($daily_work->user_id);
//                            $login_result = $Client->sign_in($Client);
                            $ci->db_model->update_reference_cursor($daily_work->reference_id, NULL);
                            echo ("<br>\n Updated Reference Cursor to NULL!!");
                            $result = $ci->db_model->delete_daily_work($daily_work->reference_id);
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

        public function process_get_insta_ref_prof_data_for_daily_report($content, \BusinessRefProfile $ref_prof) {
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

        public function set_client_cookies_by_curl(int $client_id, string $curl, int $robot_id = NULL) {
            try {
                //CONCERTAT myDB
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
                    //CONCERTAR 
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

        public function temporal_log($data)  {
            $my_file = '/var/log/dumbu.txt';
            try {
                $handle = fopen($my_file, 'a+');
                fwrite($handle, "\n\n");
                fwrite($handle, $data);
            } catch (Exception $exc) {
                //echo $exc->getTraceAsString();
            }
        }

       public function process_get_followers_error(DailyWork $daily_work, \business\cls\Client $client, int $quantity, Proxy $proxy) {
            $ci = &get_instance();
            $result = $this->RecognizeClientStatus($client);
            if (isset($result->json_response->authenticated) && $result->json_response->authenticated) {
                //retry of graph request
                $json_response = $this->get_insta_followers($result, $daily_work->rp_insta_id, $quantity, $daily_work->insta_follower_cursor, $proxy);
                if ($json_response === NULL) {
                    $ci->db_model->update_reference_cursor($daily_work->reference_id, NULL);
                    $ci->db_model->delete_daily_work($daily_work->reference_id);
                    $ci->db_model->insert_event_to_washdog($client_id, washdog_type::ERROR_IN_PR, 1, $this->id, "unexistence reference profile or may be the reference profile is block ing the client");
                } else if (isset($json_response->status) && $json_response->status == 'ok') {
                    return $json_response;
                }
                return NULL;
            } else if ($result->json_response->message == 'checkpoint_required' || $result->json_response->message == 'incorrect_password') {
                //unautorized, bloc by password or an api unrecognized error
                $msg = $result->json_response->message;
                var_dump("daily work deleted for client ($daily_work->client_id) because $msg\n");
                $ci->db_model->delete_daily_work_client($daily_work->client_id);
            }
        }
    }
}