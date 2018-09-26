<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Welcome extends CI_Controller {

    public $language = NULL;

    public function index() {
        $this->is_ip_hacker();
        $language = $this->input->get();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        if (isset($language['language']))
            $param['language'] = $language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
        $param['SCRIPT_VERSION'] = $GLOBALS['sistem_config']->SCRIPT_VERSION;
        $GLOBALS['language'] = $param['language'];
        $this->load->view('user_view', $param);
    }

    public function language() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->view('user_view', $param);
    }

    public function purchase() {
        $this->is_ip_hacker();
        $datas = $this->input->get();
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        if (isset($datas['ticket_access_token'])) {
            $this->load->model('class/client_model');
            $client = $this->client_model->get_client_by_access_token($datas['ticket_access_token'])[0];
            if (!is_array($client)) {
                header("Location: " . base_url()); die();
            } else {
                $this->user_model->update_user($client['user_id'], array(
                    'status_id' => user_status::BLOCKED_BY_INSTA));
                $this->user_model->set_sesion($client['user_id'], $this->session);
                $this->user_model->insert_washdog($client['user_id'], 'REDIRECTED FROM TICKET-BANK EMAIL LINK');
                $this->client_model->update_client($client['user_id'], array('ticket_access_token' => 'CLEAR'));
            }
        }
        if ($this->session->userdata('id')) {
            $this->user_model->insert_washdog($this->session->userdata('id'), 'SUCCESSFUL PURCHASE');
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $datas['user_id'] = $this->session->userdata('id');
            $datas['profiles'] = $this->create_profiles_datas_to_display();
            $datas['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            if (isset($datas['language']) && $datas['language'] != '') {
                $GLOBALS['language'] = $datas['language'];
            } else {
                $datas['language'] = $GLOBALS['sistem_config']->LANGUAGE;
                $GLOBALS['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            }
            $datas['Afilio_UNIQUE_ID'] = $this->session->userdata('id');
            $query = 'SELECT * FROM plane WHERE id=' . $this->session->userdata('plane_id');
            $result = $this->user_model->execute_sql_query($query);
            $datas['Afilio_order_price'] = $result[0]['initial_val'];
            $datas['Afilio_total_value'] = $result[0]['normal_val'];
            $datas['Afilio_product_id'] = $this->session->userdata('plane_id');
            $datas['client_login_profile'] = $this->session->userdata('login');
            $datas['client_email'] = $this->session->userdata('email');
            $this->client_model->Create_Followed($this->session->userdata('id'));
            $this->load->view('purchase_view', $datas);
        } else
            echo 'Access error';
    }

    public function client() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $this->load->model('class/user_role');
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_status');
        $status_description = array(1 => 'ATIVO', 2 => 'DESABILITADO', 3 => 'INATIVO', 4 => '', 5 => '', 6 => 'ATIVO'/* 'PENDENTE' */, 7 => 'NÂO INICIADO', 8 => '', 9 => 'INATIVO', 10 => 'LIMITADO');
        if (isset($this->session) && $this->session->userdata('role_id') == user_role::CLIENT) {
            $language = $this->input->get();
            if (isset($language['language'])) {
                $GLOBALS['language'] = $language['language'];
                $this->user_model->set_language_of_client($this->session->userdata('id'), $language);
            } else
                $GLOBALS['language'] = $this->user_model->get_language_of_client($this->session->userdata('id'))['language'];
            $datas1['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $datas1['WHATSAPP_PHONE'] = $GLOBALS['sistem_config']->WHATSAPP_PHONE;
            $datas1['SCRIPT_VERSION'] = $GLOBALS['sistem_config']->SCRIPT_VERSION;
            $datas1['MAX_NUM_PROFILES'] = $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT;
            $my_profile_datas = $this->external_services->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')), $this->session->userdata('login'));
            if (isset($my_profile_datas->profile_pic_url))
                $datas1['my_img_profile'] = $my_profile_datas->profile_pic_url;
            else
                $datas1['my_img_profile'] = "Blocked";
            $sql = "SELECT * FROM clients WHERE clients.user_id='" . $this->session->userdata('id') . "'";
            $init_client_datas = $this->user_model->execute_sql_query($sql);
            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "' AND type='0'";
            $reference_profile_used = $this->user_model->execute_sql_query($sql);
            $datas1['reference_profile_used'] = count($reference_profile_used);
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id') . " AND type='0'";
            $amount_followers_by_reference_profiles = $this->user_model->execute_sql_query($sql);
            $amount_followers_by_reference_profiles = (string) $amount_followers_by_reference_profiles[0]["followeds"];
            $datas1['amount_followers_by_reference_profiles'] = $amount_followers_by_reference_profiles;
            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "' AND type='1'";
            $geolocalization_used = $this->user_model->execute_sql_query($sql);
            $datas1['geolocalization_used'] = count($geolocalization_used);
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id') . " AND type='1'";
            $amount_followers_by_geolocalization = $this->user_model->execute_sql_query($sql);
            $amount_followers_by_geolocalization = (string) $amount_followers_by_geolocalization[0]["followeds"];
            $datas1['amount_followers_by_geolocalization'] = $amount_followers_by_geolocalization;
            $sql = "SELECT * FROM reference_profile WHERE client_id='" . $this->session->userdata('id') . "' AND type='2'";
            $hashtag_used = $this->user_model->execute_sql_query($sql);
            $datas1['hashtag_used'] = count($hashtag_used);
            $sql = "SELECT SUM(follows) as followeds FROM reference_profile WHERE client_id = " . $this->session->userdata('id') . " AND type='2'";
            $amount_followers_by_hashtag = $this->user_model->execute_sql_query($sql);
            $amount_followers_by_hashtag = (string) $amount_followers_by_hashtag[0]["followeds"];
            $datas1['amount_followers_by_hashtag'] = $amount_followers_by_hashtag;
            if (isset($my_profile_datas->follower_count))
                $datas1['my_actual_followers'] = $my_profile_datas->follower_count;
            else
                $datas1['my_actual_followers'] = "Blocked";
            if (isset($my_profile_datas->following))
                $datas1['my_actual_followings'] = $my_profile_datas->following;
            else
                $datas1['my_actual_followings'] = "Blocked";
            $datas1['my_sigin_date'] = $this->session->userdata('init_date');
            date_default_timezone_set('Etc/UTC');
            $datas1['today'] = date('d-m-Y', time());
            $datas1['my_initial_followers'] = $init_client_datas[0]['insta_followers_ini'];
            $datas1['my_initial_followings'] = $init_client_datas[0]['insta_following'];
            $datas1['my_login_profile'] = $this->session->userdata('login');
            $datas1['unfollow_total'] = $this->session->userdata('unfollow_total');
            $datas1['autolike'] = $this->session->userdata('autolike');
            $datas1['play_pause'] = (int) $init_client_datas[0]['paused'];
            $datas1['plane_id'] = $this->session->userdata('plane_id');
            $datas1['all_planes'] = $this->client_model->get_all_planes();
            $datas1['currency'] = $GLOBALS['sistem_config']->CURRENCY;
            $datas1['language'] = $GLOBALS['language'];

            $daily_report = $this->get_daily_report($this->session->userdata('id'));
            $datas1['followings'] = $daily_report['followings'];
            $datas1['followers'] = $daily_report['followers'];
            $datas_get = $this->input->get();
            if (($this->session->userdata('status_id') == user_status::VERIFY_ACCOUNT || $this->session->userdata('status_id') == user_status::BLOCKED_BY_INSTA)) {
                $insta_login = $this->is_insta_user($this->session->userdata('login'), $this->session->userdata('pass'), 'false');
                if ($insta_login['status'] === 'ok') {
                    if ($insta_login['authenticated']) {
                        //1. actualizar estado a ACTIVO
                        $this->user_model->update_user($this->session->userdata('id'), array(
                            'status_id' => user_status::ACTIVE));
                        if ($insta_login['insta_login_response']) {
                            //3. crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                            $active_profiles = $this->client_model->get_client_workable_profiles($this->session->userdata('id'));
                            $N = count($active_profiles);
                            for ($i = 0; $i < $N; $i++) {
                                $sql = 'SELECT * FROM daily_work WHERE reference_id=' . $active_profiles[$i]['id'];
                                $response = count($this->user_model->execute_sql_query($sql));
                                if (!$response && !$active_profiles[$i]['end_date'])
                                    $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $insta_login['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                            }
                        }
                        //4. actualizar la sesion
                        $this->user_model->set_sesion($this->session->userdata('id'), $this->session, $insta_login['insta_login_response']);
                    } else {
                        if ($insta_login['message'] == 'checkpoint_required' || $insta_login['message'] == '') {
                            //actualizo su estado
                            $this->user_model->update_user($this->session->userdata('id'), array(
                                'status_id' => user_status::VERIFY_ACCOUNT));
                            //eliminar su trabajo si contrasenhas son diferentes
                            $active_profiles = $this->client_model->get_client_workable_profiles($this->session->userdata('id'));
                            $N = count($active_profiles);
                            for ($i = 0; $i < $N; $i++) {
                                $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                            }
                            //establezco la sesion
                            $this->user_model->set_sesion($this->session->userdata('id'), $this->session);
                            $datas1['verify_account_datas'] = $insta_login;
                        } else {
                            $this->user_model->update_user($this->session->userdata('id'), array(
                                'status_id' => user_status::BLOCKED_BY_INSTA));
                            $this->user_model->set_sesion($this->session->userdata('id'), $this->session);
                        }
                    }
                } else
                if ($insta_login['status'] === 'fail') {;}
            }
            $datas1['status'] = array('status_id' => $this->session->userdata('status_id'), 'status_name' => $status_description[$this->session->userdata('status_id')]);
            $datas1['profiles'] = $this->create_profiles_datas_to_display();
            $data['head_section1'] = $this->load->view('responsive_views/client/client_header_painel', '', true);
            $data['body_section1'] = $this->load->view('responsive_views/client/client_body_painel', $datas1, true);
            $data['body_section4'] = $this->load->view('responsive_views/user/users_talkme_painel', '', true);
            $data['body_section_cancel'] = $this->load->view('responsive_views/client/client_cancel_painel', '', true);
            $data['body_section5'] = $this->load->view('responsive_views/user/users_end_painel', '', true);
            $this->load->view('client_view', $data);
        } else {
            echo "Session can't be stablished";
            $this->display_access_error();
        }
    }

    public function user_do_login($datas = NULL) {
        $this->is_ip_hacker();
        $this->load->model('class/user_role');
        $login_by_client = false;
        if (!isset($datas)) {
            $datas = $this->input->post();
            $language = $this->input->get();
            $login_by_client = true;
        }
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        if (isset($language['language']))
            $param['language'] = $language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
        $GLOBALS['language'] = $param['language'];
        $query = "SELECT * FROM users WHERE "
                . "login= '" . $datas['user_login'] . "' and pass = '" . $datas['user_pass'] . "' and role_id = '" . user_role::CLIENT . "'";
        $real_status = $this->get_real_status_of_user($query, $user, $index);
        if ($real_status == 2 || $datas['force_login'] == 'true') {
            $result = $this->user_do_login_second_stage($datas, $GLOBALS['language']);
        } else {
            if ($real_status == 1) {
                $result['message'] = $this->T('Você ainda não possue cadastro no sistema', array(), $GLOBALS['language']);
                $result['cause'] = 'empty_message';
                $result['authenticated'] = false;
            } else {
                $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                $result['cause'] = 'force_login_required';
                $result['authenticated'] = false;
            }
        }
        if ($login_by_client)
            echo json_encode($result);
        else
            return $result;
    }
    
    public function user_do_login_second_stage($datas, $language) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        if (isset($language['language']))
            $param['language'] = $language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
        $GLOBALS['language'] = $param['language'];
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');

        ($datas['force_login'] == 'true') ? $force_login = TRUE : $force_login = FALSE;
        $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass'], $force_login);
        if ($data_insta == NULL) {
            /* $result['message'] = $this->T('Não foi possível conferir suas credencias com o Instagram', array(), $GLOBALS['language']);
              $result['cause'] = 'error_login';
              $result['authenticated'] = false; */
            $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
            $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
            $result['cause'] = 'force_login_required';
            $result['authenticated'] = false;
        } else

        if ($data_insta['authenticated']) {
            //Is a DUMBU Client by Insta ds_user_id?
            $query = 'SELECT * FROM users,clients' .
                    ' WHERE clients.insta_id="' . $data_insta['insta_id'] . '" AND clients.user_id=users.id';
            $real_status = $this->get_real_status_of_user($query, $user, $index);
            if ($real_status > 1) {
                $st = (int) $user[$index]['status_id'];
                if ($st == user_status::BLOCKED_BY_INSTA || $st == user_status::VERIFY_ACCOUNT) {
                    $this->user_model->update_user($user[$index]['id'], array(
                        'name' => $data_insta['insta_name'],
                        'login' => $datas['user_login'],
                        'pass' => $datas['user_pass'],
                        'status_id' => user_status::ACTIVE));
                    if ($data_insta['insta_login_response']) {
                        //$this->client_model->update_client($user[$index]['id'], array(
                        //    'cookies' => json_encode($data_insta['insta_login_response'])));
                        $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                    }
                    if ($st != user_status::ACTIVE)
                        $this->user_model->insert_washdog($user[$index]['id'], 'FOR ACTIVE STATUS');
                    //quitar trabajo si contrasenhas son diferentes
                    $active_profiles = $this->client_model->get_client_workable_profiles($this->session->userdata('id'));
                    if ($user[$index]['pass'] != $datas['user_pass']) {
                        $N = count($active_profiles);
                        //quitar trabajo si contrasenhas son diferentes
                        for ($i = 0; $i < $N; $i++) {
                            $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                        }
                    }
                    //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                    //$active_profiles = $this->client_model->get_client_workable_profiles($this->session->userdata('id'));                                
                    if ($data_insta['insta_login_response']) {
                        $N = count($active_profiles);
                        for ($i = 0; $i < $N; $i++) {
                            $sql = 'SELECT * FROM daily_work WHERE reference_id=' . $active_profiles[$i]['id'];
                            $response = count($this->user_model->execute_sql_query($sql));
                            if (!$response && !$active_profiles[$i]['end_date'])
                                $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                        }
                    }
                    $result['resource'] = 'client';
                    $result['message'] = $this->T('Usuário @1 logueado', array(0 => $datas['user_login']), $GLOBALS['language']);
                    $result['role'] = 'CLIENT';
                    $this->client_model->Create_Followed($this->session->userdata('id'));
                    $result['authenticated'] = true;
                } else
                if ($st == user_status::ACTIVE || $st == user_status::BLOCKED_BY_PAYMENT || $st == user_status::PENDING || $st == user_status::UNFOLLOW || user_status::BLOCKED_BY_TIME) {
                    if ($st == user_status::ACTIVE) {
                        if ($user[$index]['pass'] != $datas['user_pass']) {
                            $active_profiles = $this->client_model->get_client_workable_profiles($user[$index]['id']);
                            $N = count($active_profiles);
                            //quitar trabajo si contrasenhas son diferentes
                            for ($i = 0; $i < $N; $i++) {
                                $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                            }
                            //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                            for ($i = 0; $i < $N; $i++) {
                                if (!$active_profiles[$i]['end_date'])
                                    $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                            }
                        }
                    }
                    if ($st == user_status::UNFOLLOW && $data_insta['insta_following'] < $GLOBALS['sistem_config']->INSTA_MAX_FOLLOWING - $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT) {
                        $st = user_status::ACTIVE;
                        $active_profiles = $this->client_model->get_client_workable_profiles($user[$index]['id']);
                        $N = count($active_profiles);
                        //crearle trabajo si ya tenia perfiles de referencia y si todavia no tenia trabajo insertado
                        for ($i = 0; $i < $N; $i++) {
                            if (!$active_profiles[$i]['end_date'])
                                $this->client_model->insert_profile_in_daily_work($active_profiles[$i]['id'], $data_insta['insta_login_response'], $i, $active_profiles, $this->session->userdata('to_follow'));
                        }
                    }

                    $this->user_model->update_user($user[$index]['id'], array(
                        'name' => $data_insta['insta_name'],
                        'login' => $datas['user_login'],
                        'pass' => $datas['user_pass'],
                        'status_id' => $st));
                    $cad = $this->user_model->get_status_by_id($st)['name'];
                    if ($data_insta['insta_login_response']) {
//                                $this->client_model->update_client($user[$index]['id'], array(
//                                    'cookies' => json_encode($data_insta['insta_login_response'])));
                    }
                    $this->user_model->set_sesion($user[$index]['id'], $this->session, $data_insta['insta_login_response']);
                    if ($st != user_status::ACTIVE)
                        $this->user_model->insert_washdog($this->session->userdata('id'), 'FOR STATUS ' . $cad);
                    $result['resource'] = 'client';
                    $result['message'] = $this->T('Usuário @1 logueado', array(0 => $datas['user_login']), $GLOBALS['language']);
                    $result['role'] = 'CLIENT';
                    $this->client_model->Create_Followed($this->session->userdata('id'));
                    $result['authenticated'] = true;
                } else
                if ($st == user_status::BEGINNER) {
                    $result['resource'] = 'index#lnk_sign_in_now';
                    $result['message'] = $this->T('Falha no login! Seu cadastro esta incompleto. Por favor, termine sua assinatura.', array(), $GLOBALS['language']);
                    $result['cause'] = 'signin_required';
                    $result['authenticated'] = false;
                } else
                if ($st == user_status::DELETED || $st == user_status::INACTIVE) {
                    $result['resource'] = 'index#lnk_sign_in_now';
                    $result['message'] = $this->T('Falha no login! Você deve assinar novamente para receber o serviço', array(), $GLOBALS['language']);
                    $result['cause'] = 'signin_required';
                    $result['authenticated'] = false;
                }
            } else {
                $result['resource'] = 'index#lnk_sign_in_now';
                $result['message'] = $this->T('Falha no login! Você deve assinar novamente para receber o serviço', array(), $GLOBALS['language']);
                $result['cause'] = 'signin_required';
                $result['authenticated'] = false;
            }
        } else
        if ($data_insta['message'] == 'problem_with_your_request') {
            //$GLOBALS['sistem_config'] = new \follows\cls\system_config();
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $result['resource'] = 'index#lnk_sign_in_now';
            $result['message'] = $this->T('Houve um erro inesperado. Seu problema será solucionado em breve. Tente mais tarde', array(), $GLOBALS['language']);
            $result['cause'] = 'curl_required';
            $result['authenticated'] = false;
        } else
        if ($data_insta['message'] == 'incorrect_password') {
            //Is a client with oldest Instagram credentials?
            //Buscarlo en BD por el nombre y senha
            $query = 'SELECT * FROM users' .
                    ' WHERE users.login="' . $datas['user_login'] .
                    '" AND users.pass="' . $datas['user_pass'] .
                    '" AND users.role_id="' . user_role::CLIENT . '"';
            $real_status = $this->get_real_status_of_user($query, $user, $index);
            if ($real_status > 0) {
                if ($user[$index]['status_id'] != user_status::DELETED && $user[$index]['status_id'] != user_status::INACTIVE) {
                    $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                    $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                    $result['cause'] = 'force_login_required';
                    $result['authenticated'] = false;
                } else {
                    $result['resource'] = 'index#lnk_sign_in_now';
                    $result['message'] = $this->T('Você deve assinar novamente para receber o serviço.', array(), $GLOBALS['language']);
                    $result['cause'] = 'signin_required';
                    $result['authenticated'] = false;
                }
            } else {
                //Verificar que el userLogin y respectivo ds_user_id pueden pertenecer a un usuario que
                //esta intentando entrar por 3 o mas veces con senha antigua
                //Buscarlo en BD por pk obtenido por el nombre de usuario informado
                $data_profile = $this->check_insta_profile($datas['user_login']);
                if ($data_profile) {
                    $query = 'SELECT * FROM users,clients' .
                            ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                    $real_status = $this->get_real_status_of_user($query, $user, $index);
                    if ($real_status > 0) {
                        //perfil exite en instagram y en la base de datos, senha incorrecta           
                        /* $result['message'] = $this->T('Senha incorreta!. Entre com sua senha de Instagram.', array(), $GLOBALS['language']);
                          $result['cause'] = 'error_login';
                          $result['authenticated'] = false; */
                        $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                        $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                        $result['cause'] = 'force_login_required';
                        $result['authenticated'] = false;
                    } else {
                        //el perfil existe en instagram pero no en la base de datos
                        /* $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                          $result['cause'] = 'error_login';
                          $result['authenticated'] = false; */
                    }
                } else {
                    //nombre de usuario informado no existe en instagram
                    /* $result['message'] = $this->T('Falha no login! O nome de usuário fornecido não existe no Instagram.', array(), $GLOBALS['language']);
                      $result['cause'] = 'error_login';
                      $result['authenticated'] = false; */
                    $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                    $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                    $result['cause'] = 'force_login_required';
                    $result['authenticated'] = false;
                }
            }
        } else
        if ($data_insta['message'] == 'checkpoint_required') {
            $data_profile = $this->check_insta_profile($datas['user_login']);
            $query = 'SELECT * FROM users,clients' .
                    ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
//            $user = $this->user_model->execute_sql_query($query);
            $real_status = $this->get_real_status_of_user($query, $user, $index);
            if ($real_status == 2) {
                $status_id = $user[$index]['status_id'];
                if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                    $status_id = user_status::VERIFY_ACCOUNT;
                    $this->user_model->insert_washdog($user[$index]['id'], 'FOR VERIFY ACCOUNT STATUS');
                }
                $this->user_model->update_user($user[$index]['id'], array(
                    'login' => $datas['user_login'],
                    'pass' => $datas['user_pass'],
                    'status_id' => $status_id
                ));
                $cad = $this->user_model->get_status_by_id($status_id)['name'];
                //$this->session->sess_time_to_update = 7200;
                $this->session->cookie_secure = true;
                $this->user_model->set_sesion($user[$index]['id'], $this->session);
                if ($status_id != user_status::ACTIVE)
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'FOR STATUS ' . $cad);
                $result['role'] = 'CLIENT'; // agregado por Ruslan pa resolver problema en login
                $result['resource'] = 'client';
                $result['verify_link'] = $data_insta['verify_account_url'];
                $result['return_link'] = 'client';
                $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram', array(), $GLOBALS['language']);
                $result['cause'] = 'checkpoint_required';
                $this->client_model->Create_Followed($this->session->userdata('id'));
                $result['authenticated'] = true;
            } else {
                //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                /* $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                  $result['cause'] = 'error_login';
                  $result['authenticated'] = false; */
                $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                $result['cause'] = 'force_login_required';
                $result['authenticated'] = false;
            }
        } else
        if ($data_insta['message'] == '' || $data_insta['message'] == 'phone_verification_settings') {
            if (isset($data_insta['obfuscated_phone_number'])) {
                $data_profile = $this->check_insta_profile($datas['user_login']);
                $query = 'SELECT * FROM users,clients' .
                        ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                $real_status = $this->get_real_status_of_user($query, $user, $index);
                if ($real_status == 2) {
                    $status_id = $user[$index]['status_id'];
                    if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                        $status_id = user_status::VERIFY_ACCOUNT;
                        $this->user_model->insert_washdog($user[$index]['id'], 'FOR VERIFY ACCOUNT STATUS');
                    }
                    $this->user_model->update_user($user[$index]['id'], array(
                        'login' => $datas['user_login'],
                        'pass' => $datas['user_pass'],
                        'status_id' => $status_id
                    ));
                    $cad = $this->user_model->get_status_by_id($status_id)['name'];
                    $this->user_model->set_sesion($user[$index]['id'], $this->session);
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'FOR STATUS ' . $cad);
                    $result['return_link'] = 'index';
                    $result['message'] = $this->T('Sua conta precisa ser verificada no Instagram com código enviado ao numero de telefone que comtênm os digitos ', array(0 => $data_insta['obfuscated_phone_number']), $GLOBALS['language']);
                    $result['cause'] = 'phone_verification_settings';
                    $result['verify_link'] = '';
                    $result['obfuscated_phone_number'] = $data_insta['obfuscated_phone_number'];
                    $result['authenticated'] = false;
                } else {
                    //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                    /* $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                      $result['cause'] = 'error_login';
                      $result['authenticated'] = false; */
                    $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                    $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                    $result['cause'] = 'force_login_required';
                    $result['authenticated'] = false;
                }
            } else
            if ($data_insta['message'] === 'empty_message') {
                $data_profile = $this->check_insta_profile($datas['user_login']);
                $query = 'SELECT * FROM users,clients' .
                        ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                $real_status = $this->get_real_status_of_user($query, $user, $index);
                if ($real_status == 2) {
                    $status_id = $user[$index]['status_id'];
                    if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                        $status_id = user_status::VERIFY_ACCOUNT;
                        $this->user_model->insert_washdog($user[$index]['id'], 'FOR VERIFY ACCOUNT STATUS');
                    }
                    $this->user_model->update_user($user[$index]['id'], array(
                        'login' => $datas['user_login'],
                        'pass' => $datas['user_pass'],
                        'status_id' => $status_id
                    ));
                    $cad = $this->user_model->get_status_by_id($status_id)['name'];
                    $this->user_model->set_sesion($user[$index]['id'], $this->session);
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'FOR STATUS ' . $cad);
                    $result['resource'] = 'client';
                    $result['return_link'] = 'index';
                    $result['verify_link'] = '';
                    $result['message'] = $this->T('Sua conta esta presentando problemas temporalmente no Instagram. Entre em contato conosco para resolver o problema', array(), $GLOBALS['language']);
                    $result['cause'] = 'empty_message';
                    $result['authenticated'] = false;
                } else {
                    //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                    /* $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                      $result['cause'] = 'error_login';
                      $result['authenticated'] = false; */
                    $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                    $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                    $result['cause'] = 'force_login_required';
                    $result['authenticated'] = false;
                }
            } else
            if ($data_insta['message'] == 'unknow_message') {
                $data_profile = $this->check_insta_profile($datas['user_login']);
                $query = 'SELECT * FROM users,clients' .
                        ' WHERE clients.insta_id="' . $data_profile->pk . '" AND clients.user_id=users.id';
                $real_status = $this->get_real_status_of_user($query, $user, $index);
                if ($real_status == 2) {
                    $status_id = $user[$index]['status_id'];
                    if ($user[$index]['status_id'] != user_status::BLOCKED_BY_PAYMENT && $user[$index]['status_id'] != user_status::PENDING) {
                        $status_id = user_status::VERIFY_ACCOUNT;
                        $this->user_model->insert_washdog($user[$index]['id'], 'FOR VERIFY ACCOUNT STATUS');
                    }
                    $this->user_model->update_user($user[$index]['id'], array(
                        'login' => $datas['user_login'],
                        'pass' => $datas['user_pass'],
                        'status_id' => $status_id
                    ));
                    $cad = $this->user_model->get_status_by_id($status_id)['name'];
                    if ($st != user_status::ACTIVE)
                        $this->user_model->insert_washdog($user[$index]['id'], 'FOR STATUS ' . $cad);
                    $result['resource'] = 'client';
                    $result['return_link'] = 'index';
                    $result['verify_link'] = '';
                    $result['message'] = $data_insta['unknow_message'];
                    $result['cause'] = 'unknow_message';
                    $result['authenticated'] = false;
                } else {
                    //usuario informado no es usuario de dumbu y lo bloquearon por mongolico
                    /* $result['message'] = $this->T('Falha no login! Certifique-se de que possui uma assinatura antes de entrar.', array(), $GLOBALS['language']);
                      $result['cause'] = 'error_login';
                      $result['authenticated'] = false; */
                    $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
                    $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
                    $result['cause'] = 'force_login_required';
                    $result['authenticated'] = false;
                }
            }
        } else {
            /* $result['message'] = $this->T('Se o problema no login continua, por favor entre em contato com o Atendimento', array(), $GLOBALS['language']);
              $result['cause'] = 'error_login';
              $result['authenticated'] = false; */
            $result['message'] = $this->T('Credenciais erradas', array(), $GLOBALS['language']);
            $result['message_force_login'] = $this->T('Seguro que são suas credencias de IG', array(), $GLOBALS['language']);
            $result['cause'] = 'force_login_required';
            $result['authenticated'] = false;
        }
        if ($result['authenticated'] == true) {
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'), 'DID LOGIN ');
        }
        return $result;
    }

    public function get_real_status_of_user($query, &$user, &$index) {
        $this->is_ip_hacker();
        $this->load->model('class/user_status');
        $this->load->model('class/user_model');
        $user = $this->user_model->execute_sql_query($query);
        $N = count($user);
        $real_status = 0; //No existe, eliminado o inactivo
        $index = 0;
        for ($i = 0; $i < $N; $i++) {
            if ($user[$i]['status_id'] == user_status::BEGINNER) {
                $real_status = 1; //Beginner
                $index = $i;
                break;
            } else
            if ($user[$i]['status_id'] != user_status::DELETED && $user[$i]['status_id'] != user_status::INACTIVE && $user[$i]['status_id'] < user_status::DONT_DISTURB) {
                $real_status = 2; //cualquier otro estado
                $index = $i;
                break;
            }
        }
        return $real_status;
    }
    
    public function check_ticket_peixe_urbano() {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $datas = $this->input->post();
        if (true) {
            $this->client_model->update_client($datas['pk'], array(
                'ticket_peixe_urbano' => $datas['cupao_number']));
            $result['success'] = true;
            $result['message'] = 'CUPOM de desconto verificado corretamennte';
        } else {
            $result['success'] = false;
            $result['message'] = 'CUPOM de desconto incorreto';
        }
        echo json_encode($result);
    }

    //Passo 1. Chequeando usuario em IG y enviando email al usuario con código para entrar al paso 2
    public function check_user_for_sing_in($datas = NULL) { //sign in with passive instagram profile verification
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/user_role');        
        $origin_datas = $datas;
        if (!$datas) {
            $datas = $this->input->post();
            $GLOBALS['language'] = $datas['language'];
        }
        $datas['utm_source'] = isset($datas['utm_source']) ? urldecode($datas['utm_source']) : "NULL";
        $data_insta = $this->check_insta_profile($datas['client_login']);
        if ($data_insta) {
            if (!isset($data_insta->following))
                $data_insta->following = 0;
            $query = 'SELECT * FROM users,clients WHERE clients.insta_id="' . $data_insta->pk . '"' .
                    'AND clients.user_id=users.id';
            $client = $this->user_model->execute_sql_query($query);
            $N = count($client);
            $real_status = -1; //No existe
            $early_client_canceled = false;
            $index = 0;
            for ($i = 0; $i < $N; $i++) {
                if ($client[$i]['status_id'] == user_status::DELETED || $client[$i]['status_id'] == user_status::INACTIVE) {
                    $real_status = 0; //cancelado o inactivo
                    $early_client_canceled = true;
                    $index = $i;
                } else
                if ($client[$i]['status_id'] == user_status::BEGINNER) {
                    $real_status = 1; //Beginner
                    $index = $i;
                    break;
                } else
                if ($client[$i]['status_id'] != user_status::DELETED && $client[$i]['status_id'] != user_status::INACTIVE) {
                    $real_status = 2; //cualquier otro estado
                    break;
                }
            }
            if ($real_status == -1 || $real_status == 0) {
                $datas['role_id'] = user_role::CLIENT;
                $datas['status_id'] = user_status::BEGINNER;
                $datas['HTTP_SERVER_VARS'] = json_encode($_SERVER);
                $datas['purchase_counter'] = $GLOBALS['sistem_config']->MAX_PURCHASE_RETRY;
                $id_user = $this->client_model->insert_client($datas, $data_insta);
                $response['pk'] = (string) $id_user;
                if ($real_status == 0 || $early_client_canceled)
                    $response['early_client_canceled'] = true;
                else
                    $response['early_client_canceled'] = false;
                $response['datas'] = json_encode($data_insta);
                $response['success'] = true;
                $security_code = rand(100000, 999999);
                $this->security_purchase_code = md5("$security_code");
                //TODO: enviar para el navegador los datos del usuario logueado en las cookies para chequearlas en los PASSOS 2 y 3
            } else {
                if ($real_status == 1) {
                    $this->user_model->update_user($client[$i]['id'], array(
                        'name' => $data_insta->full_name,
                        'email' => $datas['client_email'],
                        'login' => $datas['client_login'],
                        'pass' => $datas['client_pass'],
                        'language' => $GLOBALS['language'],
                        'init_date' => time()));
                    $this->client_model->update_client($client[$i]['id'], array(
                        'insta_followers_ini' => $data_insta->follower_count,
                        'insta_following' => $data_insta->following,
                        'utm_source' => $datas['utm_source'],
                        'HTTP_SERVER_VARS' => json_encode($_SERVER)));

                    $this->client_model->insert_initial_instagram_datas($client[$i]['id'], array(
                        'followers' => $data_insta->follower_count,
                        'followings' => $data_insta->following,
                        'date' => time()));
                    $response['datas'] = json_encode($data_insta);
                    if ($early_client_canceled)
                        $response['early_client_canceled'] = true;
                    else
                        $response['early_client_canceled'] = false;
                    $response['pk'] = $client[$index]['user_id'];
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['message'] = $this->T('O usuario informado já tem cadastro no sistema.', array(), $GLOBALS['language']);
                }
            }
            if ($response['success'] == true) {
                $response['need_delete'] = ($GLOBALS['sistem_config']->INSTA_MAX_FOLLOWING - $data_insta->following);
                $response['MIN_MARGIN_TO_INIT'] = $GLOBALS['sistem_config']->MIN_MARGIN_TO_INIT;
                // Enviar email al usuario con codigo de verificacion para pasar al paso 2
                $purchase_access_token = mt_rand(1000, 9999);
                $this->client_model->update_client($response['pk'], array('purchase_access_token' => $purchase_access_token));
                $this->load->library('external_services');
                $result = $this->external_services->send_user_to_purchase_step($datas['client_email'], $data_insta->full_name, $datas['client_login'], $purchase_access_token);
                if ($result['success']) {
                    $response['cause'] = 'email_send';
                    $response['message'] = $this->T('Para continuar o cadastro deve inserir o código enviado ao email fornecido!', array(), $GLOBALS['language']);
                } else {
                    $response['cause'] = 'email_not_send';
                    $response['message'] = $this->T('Não foi possível enviar o email de confirmação ao endereço fornecido!', array(), $GLOBALS['language']);
                }
            }
        } else {
            $response['success'] = false;
            $response['cause'] = 'missing_user';
            $response['message'] = $this->T('O nome de usuario informado não é um perfil do Instagram.', array(), $GLOBALS['language']);
        }
        if (!$origin_datas)
            echo json_encode($response);
        else
            return $response;
    }

    //Passo 2.1 Pagamento por boleto bancario
    public function check_client_ticket_bank($datas = NULL) {
        //OBS: o cliente ainda continua em BEGINNER, quem ativa é a notificação da mindipagg de boleto pago
        $this->is_ip_hacker();
        //0. Carregar librarias e datas vindo do navegador        
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $origin_datas = $datas;
        $datas = $this->input->post();
        $datas['plane_id'] = intval($datas['plane_type']);
        $datas['ticket_bank_option'] = intval($datas['ticket_bank_option']);
        $client_datas = $this->client_model->get_all_data_of_client($datas['pk'])[0];
        //1. analisar se é possivel gerar boleto para esse cliente
        $purchase_counter = (int) $client_datas['purchase_counter'];
        $elapsed_time = strtotime('-2 days', time());
        $amount_unpayed_tickets = $this->client_model->get_unpayed_tickets($datas['pk'], $elapsed_time);
        if (count($amount_unpayed_tickets) >= 2) {
            $result['success'] = false;
            $result['message'] = 'Tem excedido a quantidade máxima de boletos gerados';
        } else
        if (!$purchase_counter > 0) {
            $result['success'] = false;
            $result['message'] = 'Número de tentativas esgotadas. Contate nosso atendimento';
        } else
        //2. analisar o código de verificação recebido no passo 1 da assinatura
        if ($datas['purchase_access_token'] != $client_datas['purchase_access_token']) {
            $this->client_model->decrement_purchase_retry($datas['pk'], 0);
            $result['success'] = false;
            $result['message'] = 'Sorry!! Not possible violate our security protections.';
        } else
        //3. conferir los datos recebidos
        if (!$this->validaCPF($datas['cpf'])) {
            $value['purchase_counter'] = $purchase_counter - 1;
            $this->client_model->decrement_purchase_retry($datas['pk'], $value);
            $result['success'] = false;
            $result['message'] = 'CPF incorreto';
        } else
        if (!( $datas['plane_id'] > 1 && $datas['plane_id'] <= 5 )) {
            $value['purchase_counter'] = $purchase_counter - 1;
            $this->client_model->decrement_purchase_retry($datas['pk'], $value);
            $result['success'] = false;
            $result['message'] = 'Plano informado incorreto';
        } else
        if (!( $datas['ticket_bank_option'] >= 1 && $datas['ticket_bank_option'] <= 3 )) {
            $value['purchase_counter'] = $purchase_counter - 1;
            $this->client_model->decrement_purchase_retry($datas['pk'], $value);
            $result['success'] = false;
            $result['message'] = 'Selecione um periodo de tempo válido pra ganhar desconto';
        } else {
            //4. gerar boleto bancario
            $this->load->model('class/user_model');
            $query = 'SELECT * FROM plane WHERE id=' . $datas['plane_id'];
            $plane_datas = $this->user_model->execute_sql_query($query)[0];
            if ($datas['ticket_bank_option'] == 1) {
                $datas['AmountInCents'] = intval($plane_datas['normal_val'] * 0.85 * 3);
                $amount_months = 3;
            } else
            if ($datas['ticket_bank_option'] == 2) {
                $datas['AmountInCents'] = intval($plane_datas['normal_val'] * 0.75 * 6);
                $amount_months = 6;
            } else
            if ($datas['ticket_bank_option'] == 3) {
                $datas['AmountInCents'] = intval($plane_datas['normal_val'] * 0.60 * 12);
                $amount_months = 12;
            }
            $DocumentNumber = $GLOBALS['sistem_config']->TICKET_BANK_DOCUMENT_NUMBER;
            $datas['DocumentNumber'] = $DocumentNumber + 1;
            $datas['OrderReference'] = $DocumentNumber + 1;
            $datas['user_id'] = $datas['pk'];
            $datas['name'] = $datas['ticket_bank_client_name'];
            //4.1 actualizar el TICKET_BANK_DOCUMENT_NUMBER con el valor em $DocumentNumber
            $query = "UPDATE dumbu_system_config set value = " . $datas['DocumentNumber'] . " WHERE name='TICKET_BANK_DOCUMENT_NUMBER'";
            $this->client_model->execute_sql_query_to_update($query);
            try {
                $response = $this->check_mundipagg_boleto($datas);
            } catch (Exception $exc) {
                $result['success'] = false;
                $result['exception'] = $exc->getTraceAsString();
                $result['message'] = 'Erro gerando o boleto bancário';
            }
            //5. salvar dados
            if (!$response['success']) {
                $result['success'] = false;
                $result['exception'] = $exc->getTraceAsString();
                $result['message'] = 'Erro gerando boleto bancário';
            } else {
                //5.1 insertar o novo boleto gerado no banco de dados
                $ticket_url = $response['ticket_url'];
                $ticket_order_key = $response['ticket_order_key'];
                $ticket_datas = array(
                    'client_id' => $datas['pk'],
                    'name_in_ticket' => $datas['ticket_bank_client_name'],
                    'cpf' => $datas['cpf'],
                    'ticket_bank_option' => $datas['ticket_bank_option'],
                    'cep' => $datas['cep'],
                    'street_address' => $datas['street_address'],
                    'house_number' => $datas['house_number'],
                    'neighborhood_address' => $datas['neighborhood_address'],
                    'municipality_address' => $datas['municipality_address'],
                    'state_address' => $datas['state_address'],
                    'ticket_link' => $ticket_url,
                    'ticket_order_key' => $ticket_order_key,
                    'amount_months' => $amount_months,
                    'document_number' => $datas['DocumentNumber'],
                    'generated_date' => time()
                );
                $this->client_model->insert_ticket_bank_generated($ticket_datas);
                //5.2 decrementar o purchase counter em 2
                $value['purchase_counter'] = $purchase_counter - 2;
                $this->client_model->decrement_purchase_retry($datas['pk'], $value);

                //6. enviar email com link do boleto e o link da success_purchase com access token encriptada com md5            
                $insta_id = $client_datas['insta_id'];
                $access_link = base_url() . 'index.php/welcome/purchase'
                        . '?client_id=' . urlencode($this->Crypt->codify_level1($datas['pk']))
                        . '&ticket_access_token=' . md5($datas['pk'] . '-abc-' . $insta_id . '-cba-' . '8053');
                $username = $client_datas['login'];
                $useremail = $client_datas['email'];
                //6.1 salvar access token y atualizar pay_day
                $this->client_model->update_client($client_datas['user_id'], array(
                    'credit_card_number' => 'PAYMENT_BY_TICKET_BANK',
                    'credit_card_name' => 'PAYMENT_BY_TICKET_BANK',
                    'pay_day' => strtotime("+7 days", time()),
                    'ticket_access_token' => md5($datas['pk'] . '-abc-' . $insta_id . '-cba-' . '8053')
                ));
                $this->load->library('external_services');
                $email = $this->external_services->send_link_ticket_bank_and_access_link($username, $useremail, $access_link, $ticket_url);
                //7. retornar response e tomar decisão no cliente
                if ($email['success']) {
                    $result['success'] = true;
                } else {
                    $result['success'] = false;
                    $result['message'] = 'Contate nosso atendimento e aguarde as instruções. Houve problema ao enviar email com as instruções';
                }
            }
        }        
        echo json_encode($result);
    }

    //Passo 2.2 Chequeando datos bancarios y guardando datos y estado del cliente pagamento  
    public function check_client_data_bank($datas = NULL) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/credit_card_status');
        $this->load->library('external_services');
        $GLOBALS['sistem_config'] = $this->system_config->load();        
        $origin_datas = $datas;
        if ($datas == NULL)
            $datas = $this->input->post();
        $query = $this->client_model->get_all_data_of_client($datas['pk']);
        $datas['user_login'] = $query[0]['login'];
        $datas['user_pass'] = $query[0]['pass'];
        $datas['user_email'] = $query[0]['email'];
        $datas['insta_id'] = $query[0]['insta_id'];
        $datas['status_id'] = $query[0]['status_id'];
        $purchase_counter = (int) $query[0]['purchase_counter'];
        $kk = $query[0]['purchase_access_token'];
        if ($query[0]['purchase_access_token'] === $datas['purchase_access_token']) {
            if ($datas['status_id'] === '8' || $datas['status_id'] === '4') {
                if ($purchase_counter > 0) {
                    if ($this->validate_post_credit_card_datas($datas)) {
                        try {
                            //1. salvar datos del carton de credito
                            $this->client_model->update_client($datas['pk'], array(
                                'credit_card_number' => $this->Crypt->codify_level1($datas['credit_card_number']),
                                'credit_card_cvc' => $this->Crypt->codify_level1($datas['credit_card_cvc']),
                                'credit_card_name' => $datas['credit_card_name'],
                                'credit_card_exp_month' => $datas['credit_card_exp_month'],
                                'credit_card_exp_year' => $datas['credit_card_exp_year']
                            ));
                            if (isset($datas['ticket_peixe_urbano'])) {
                                $ticket = trim($datas['ticket_peixe_urbano']);
                                $this->client_model->update_client($datas['pk'], array(
                                    'ticket_peixe_urbano' => $ticket
                                ));
                            }
                            //2. hacer el pagamento segun el plano
                            $response['success'] = false;
                            if ($datas['plane_type'] >= '1' && $datas['plane_type'] <= '5') {
                                //2.1 crear cliente en la vindi
                                //$gateway_client_id = $this->Vindi->addClient($datas['credit_card_name'], $datas['user_email']);
                                $gateway_client_id = $this->external_services->addClient($datas['credit_card_name'], $datas['user_email']);
                                if ($gateway_client_id) {
                                    if ($datas['plane_type'] == '1')
                                        $datas['plane_type'] = 4;
                                    $this->client_model->set_client_payment(
                                            $datas['pk'], $gateway_client_id, $datas['plane_type']);
                                    $datas['pay_day'] = strtotime("+" . $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS . " days", time());
                                    $this->client_model->update_client(
                                            $datas['pk'], array('pay_day' => $datas['pay_day'], 'plane_id' => $datas['plane_type']
                                    ));
                                    //2.2. crear carton en la vindi
                                    //$resp1 = $this->Vindi->addClientPayment($datas['pk'], $datas);
                                    $resp1 = $this->external_services->addClientPayment($datas['pk'], $datas);
                                    if ($resp1->success) {
                                        //2.3. crear recurrencia segun plano-producto
                                        //$resp2 = $this->Vindi->create_recurrency_payment($datas['pk'], $datas['pay_day'], $datas["plane_type"]);
                                        $resp2 = $this->external_services->create_recurrency_payment($datas['pk'], $datas['pay_day'], $datas["plane_type"]);
                                        if ($resp2->success) {
                                            //2.4 salvar payment_key (order_key)
                                            $this->client_model->update_client_payment($datas['pk'], array('payment_key' => $resp2->payment_key));
                                            $response['success'] = true;
                                        } else
                                            $response['message'] = $resp2->message;
                                    } else
                                        $response['message'] = $resp1->message;
                                }
                            }
                            //3. si pagamento correcto: logar cliente, establecer sesion, actualizar status, emails, initdate
                            if ($response['success']) {
                                $this->client_model->update_client($datas['pk'], array('purchase_access_token' => '0'));
                                $this->load->model('class/user_model');
                                $data_insta = $this->is_insta_user($datas['user_login'], $datas['user_pass'], $datas['force_login']);
                                if ($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                                    $datas['status_id'] = user_status::ACTIVE;
                                    $this->user_model->update_user($datas['pk'], array(
                                        'init_date' => time(),
                                        'status_id' => $datas['status_id']));
                                    $this->user_model->set_sesion($datas['pk'], $this->session, $data_insta['insta_login_response']);
                                } else
                                if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']) {
                                    $this->user_model->update_user($datas['pk'], array(
                                        'init_date' => time(),
                                        'status_id' => user_status::BLOCKED_BY_INSTA));
                                    $this->user_model->set_sesion($datas['pk'], $this->session);
                                } else
                                if ($data_insta['status'] === 'fail' && $data_insta['message'] == 'checkpoint_required') {
                                    $this->user_model->update_user($datas['pk'], array(
                                        'init_date' => time(),
                                        'status_id' => user_status::VERIFY_ACCOUNT));
                                    $result['resource'] = 'client';
                                    $result['verify_link'] = $data_insta['verify_account_url'];
                                    $result['return_link'] = 'client';
                                    $result['message'] = 'Sua conta precisa ser verificada no Instagram';
                                    $result['cause'] = 'checkpoint_required';
                                    $this->user_model->set_sesion($datas['pk'], $this->session);
                                } else
                                if ($data_insta['status'] === 'fail' && $data_insta['message'] == '') {
                                    $this->user_model->update_user($datas['pk'], array(
                                        'init_date' => time(),
                                        'status_id' => user_status::VERIFY_ACCOUNT));
                                    $result['resource'] = 'client';
                                    $result['verify_link'] = '';
                                    $result['return_link'] = 'client';
                                    $this->user_model->set_sesion($datas['pk'], $this->session);
                                } else {
                                    $this->user_model->update_user($datas['pk'], array(
                                        'init_date' => time(),
                                        'status_id' => user_status::BLOCKED_BY_INSTA));
                                    $this->user_model->set_sesion($datas['pk'], $this->session);
                                }
                                //Email com compra satisfactoria a atendimento y al cliente
                                if ($data_insta['status'] === 'ok' && $data_insta['authenticated'])
                                    $this->email_success_buy_to_client($datas['user_email'], $data_insta['insta_name'], $datas['user_login'], $datas['user_pass']);
                                else
                                    $this->email_success_buy_to_client($datas['user_email'], $datas['user_login'], $datas['user_login'], $datas['user_pass']);
                                $result['success'] = true;
                                $result['message'] = $this->T('Usuário cadastrado com sucesso', array(), $GLOBALS['language']);
                                $this->client_model->update_client($datas['pk'], array('purchase_access_token' => '0'));
                            } else {
                                $value['purchase_counter'] = $purchase_counter - 1;
                                $this->client_model->decrement_purchase_retry($datas['pk'], $value);
                                $result['success'] = false;
                                $result['message'] = $response['message'];
                            }
                        } catch (Exception $exc) {
                            $result['success'] = false;
                            $result['exception'] = $exc->getTraceAsString();
                            $result['message'] = $this->T('Error actualizando en base de datos', array(), $GLOBALS['language'], $GLOBALS['language']);
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('Acesso não permitido', array(), $GLOBALS['language']);
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Alcançõu a quantidade máxima de retentativa de compra, por favor, entre en contato con o atendimento', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Acesso não permitido', array(), $GLOBALS['language']);
            }
        } else {
            $this->client_model->update_client($datas['pk'], array('retry_payment_counter' => '0'));
            $result['success'] = false;
            $result['message'] = $this->T('Acesso não permitido', array(), $GLOBALS['language']);
        }
        if (!$origin_datas)
            echo json_encode($result);
        else
            return $result;
    }

    public function update_client_datas() {
        $this->is_ip_hacker();
        $this->load->model('class/Crypt');
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/credit_card_status');
        $this->load->model('class/external_services');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        //require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/PaymentVindi.php';
        //$this->Vindi = new \follows\cls\Payment\Vindi();
        $language = $this->input->get();
        $datas = $this->input->post();
        if ($this->session->userdata('id')) {
            if ($this->validate_post_credit_card_datas($datas)) {
                $client_data = $this->client_model->get_client_by_id($this->session->userdata('id'))[0];
                $client_vindi_payment = $this->client_model->get_vindi_payment($this->session->userdata('id'));
                //0. atualizar dados no banco de dados
                $this->user_model->update_user($this->session->userdata('id'), array(
                    'email' => $datas['client_email']));
                $this->client_model->update_client($this->session->userdata('id'), array(
                    'credit_card_number' => $this->Crypt->codify_level1($datas['credit_card_number']),
                    'credit_card_cvc' => $this->Crypt->codify_level1($datas['credit_card_cvc']),
                    'credit_card_name' => $datas['credit_card_name'],
                    'credit_card_exp_month' => $datas['credit_card_exp_month'],
                    'credit_card_exp_year' => $datas['credit_card_exp_year'],
                    'pay_day' => $datas['pay_day']
                ));
                ///1. verificar si el cliente existe o no en la vindi
                $is_vindi_client = $this->client_model->is_vindi_client($this->session->userdata('id'));
                if (!$is_vindi_client) {
                    $gateway_client_id = $this->external_services->addClient($datas['credit_card_name'], $datas['client_email']);
                    if ($gateway_client_id) {
                        if ($datas['client_update_plane'] == '1')
                            $datas['client_update_plane'] = 4;
                        $this->client_model->set_client_payment(
                                $this->session->userdata('id'), $gateway_client_id, $datas['client_update_plane']);
                    }
                }
                if ($is_vindi_client || $gateway_client_id) {
                    //2. crear el nuevo carton en la vindi
                    $resp = $this->external_services->addClientPayment($client_data['user_id'], $datas);
                    //3. cobrar segun status y upgrade
                    if ($datas['client_update_plane'] == 1)
                        $datas['client_update_plane'] = 4;
                    $UPGRADE_PLANE = ($datas['client_update_plane'] > $this->session->userdata('plane_id'));
                    $BLOCKED_BY_PAYMENT = ($this->session->userdata('status_id') == user_status::BLOCKED_BY_PAYMENT);
                    $PENDING = ($this->session->userdata('status_id') == user_status::PENDING);
                    $recurrency_date = 0;
                    $recurrency_value = 0;
                    $pay_now_value = 0;
                    if ($BLOCKED_BY_PAYMENT) {
                        if ($UPGRADE_PLANE) {
                            //crear recurrencia para ahora con valor de nuevo plano
                            $recurrency_date = time();
                            $recurrency_value = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                        } else {
                            //crear recurrencia para ahora con valor de nuevo actual
                            $recurrency_date = time();
                            $recurrency_value = $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                        }
                    } else {
                        if ($UPGRADE_PLANE) {
                            //crear cobranza en la hora con diferencia entre planos
                            $pay_now_value = $this->client_model->get_normal_pay_value($datas['client_update_plane']) -
                                    $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                            //crear recurrencia para dia normal con valor de nuevo plano
                            $recurrency_date = $this->get_pay_day($client_data['pay_day'])['pay_day'];
                            $recurrency_value = $this->client_model->get_normal_pay_value($datas['client_update_plane']);
                        } else {
                            //crear recurrencia para dia de pagamento con valor de plano actual
                            $recurrency_date = $this->get_pay_day($client_data['pay_day'])['pay_day'];
                            $recurrency_value = $this->client_model->get_normal_pay_value($this->session->userdata('plane_id'));
                        }
                    }
                    //4. hacer un pagamento ahora si necesitara 
                    if ($pay_now_value) {
                        $this->client_model->update_client($this->session->userdata('id'), array('pay_day' => $recurrency_date));
                        $amount = (int) ($pay_now_value / 100);
                        //$resp = $this->Vindi->create_payment($this->session->userdata('id'), \follows\cls\Payment\Vindi::prod_1real_id, $amount);
                        $resp = $this->external_services->create_payment($this->session->userdata('id'), $GLOBALS['sistem_config']->prod_1real_id, $amount);
                        if ($resp->success && $resp->status == 'active')
                            $flag_pay_now = true;
                    }
                    //5. recurrencia
                    $resp_recurrency = $this->external_services->create_recurrency_payment($this->session->userdata('id'), $recurrency_date, $datas['client_update_plane']);
                    if ($resp_recurrency->success) {
                        $flag_pay_day = true;
                        //5.1 cancelar recurrencia antigua 
                        if (count($client_vindi_payment))
                            //$this->Vindi->cancel_recurrency_payment($client_vindi_payment['payment_key']);
                            $this->external_services->cancel_recurrency_payment($client_vindi_payment['payment_key']);
                        //5.2 salvar nuevo order_key (payment_key)
                        $this->client_model->update_client_payment($this->session->userdata('id'), array('payment_key' => $resp_recurrency->payment_key,
                            'dumbu_plane_id' => $datas['client_update_plane']
                        ));
                        //5.3 actualizar nuevo plano y pay_day
                        $this->client_model->update_client($this->session->userdata('id'), array(
                            'plane_id' => $datas['client_update_plane'],
                            'pay_day' => $recurrency_date));
                        //5.4 actualizar el status del cliente despues de la actualizacion de CC
                        if ($BLOCKED_BY_PAYMENT || $PENDING) {
                            $datas['status_id'] = user_status::ACTIVE;
                        } else
                            $datas['status_id'] = $this->session->userdata('status_id');
                        $this->user_model->update_user($this->session->userdata('id'), array(
                            'status_id' => $datas['status_id']));
                        //5.5 actualizar sesion actual
                        $this->session->set_userdata('plane_id', $datas['client_update_plane']);
                        $this->session->set_userdata('status_id', $datas['status_id']);
                        //5.6 fin
                        $result['success'] = true;
                        $result['resource'] = 'client';
                        $result['message'] = $this->T('Dados bancários atualizados corretamente', array(), $GLOBALS['language']);
                        $result['response_delete_early_payment'] = $response_delete_early_payment;
                    }
                    if (($payments_days['pay_now'] && !$flag_pay_now) || (!$payments_days['pay_now'] && !$flag_pay_day)) {
                        //restablecer en la base de datos los datos anteriores
                        $this->client_model->update_client($this->session->userdata('id'), array(
                            'credit_card_number' => $this->Crypt->codify_level1($client_data['credit_card_number']),
                            'credit_card_cvc' => $this->Crypt->codify_level1($client_data['credit_card_cvc']),
                            'credit_card_name' => $client_data['credit_card_name'],
                            'credit_card_exp_month' => $client_data['credit_card_exp_month'],
                            'credit_card_exp_year' => $client_data['credit_card_exp_year'],
                            'pay_day' => $client_data['pay_day'],
                            'order_key' => $client_data['order_key']
                        ));
                        $result['success'] = false;
                        $result['resource'] = 'client';
                        if ($payments_days['pay_now'] && !$flag_pay_now)
                            $result['message'] = is_array($resp_pay_now) ? $resp_pay_now["message"] : $this->T("Erro inesperado! Provávelmente Cartão inválido, entre em contato com o atendimento.", array(), $GLOBALS['language']);
                        else
                            $result['message'] = is_array($resp_recurrency) ? $resp_recurrency["message"] : $this->T("Erro inesperado! Provávelmente Cartão inválido, entre em contato com o atendimento.", array(), $GLOBALS['language']);
                    } else
                    if (($payments_days['pay_now'] && $flag_pay_now && !$flag_pay_day)) {
                        //se hiso el primer pagamento bien, pero la recurrencia mal
                        $result['success'] = true;
                        $result['resource'] = 'client';
                        $result['message'] = $this->T('Actualização bem sucedida, mas deve atualizar novamente até a data de pagamento ( @1 )', array(0 => $payments_days['pay_now']));
                    }
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Acesso não permitido', array(), $GLOBALS['language']);
            }
            if ($this->session->userdata('id') && $result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'CORRECT CARD UPDATE');
            } else {
                if ($this->session->userdata('id')) {
                    $this->load->model('class/user_model');
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'INCORRECT CARD UPDATE');
                }
            }
            echo json_encode($result);
        }
    }

    public function get_pay_day($pay_day) {
        $this->is_ip_hacker();
        $this->load->model('class/user_status');
        $now = time();
        $datas['pay_now'] = false;
        $d_today = date("j", $now);
        $m_today = date("n", $now);
        $y_today = date("Y", $now);
        $d_pay_day = date("j", $pay_day);
        $m_pay_day = date("n", $pay_day);
        $y_pay_day = date("Y", $pay_day);
        if ($now < $pay_day) {
            $datas['pay_day'] = $pay_day;
        } else
        if ($d_today < $d_pay_day) {
            if ($this->session->userdata('status_id') == (string) user_status::PENDING)
                $datas['pay_now'] = true;
            $previous_month = strtotime("-30 days", $now); //1. mes anterior respecto a hoy            
            $previous_payment_date = strtotime($d_pay_day . '-' . date("n", $previous_month) . '-' . date("Y", $previous_month)); //2. dia de pagamento en el mes anterior al actual            
            $datas['pay_day'] = strtotime("+30 days", $previous_payment_date); //3. nuevo dia de pagamento para el mes actual
        } else
        if ($d_today > $d_pay_day) {
            //0. si pendiente por pagamento, inidcar que se debe hacer pagamento
            if ($this->session->userdata('status_id') == (string) user_status::PENDING)
                $datas['pay_now'] = true;
            $recorrency_date = strtotime($d_pay_day . '-' . $m_today . '-' . $y_today); //mes actual com el dia de pagamento
            $datas['pay_day'] = strtotime("+30 days", $recorrency_date); //proximo mes
        } else
            $datas['pay_day'] = false;
        return $datas;
    }

    public function detectCardType($num) {
        $this->is_ip_hacker();
        $re = array(
            "visa" => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex" => "/^3[47][0-9]{13}$/",
            "discover" => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
            "diners" => "/^3[068]\d{12}$/",
            "elo" => "/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/",
            "hipercard" => "/^(606282\d{10}(\d{3})?)|(3841\d{15})$/"
        );
        if (preg_match($re['visa'], $num)) {
            return 'Visa';
        } else if (preg_match($re['mastercard'], $num)) {
            return 'Mastercard';
        } else if (preg_match($re['amex'], $num)) {
            return 'Amex';
        } else if (preg_match($re['discover'], $num)) {
            return 'Discover';
        } else if (preg_match($re['diners'], $num)) {
            return 'Diners';
        } else if (preg_match($re['elo'], $num)) {
            return 'Elo';
        } else if (preg_match($re['hipercard'], $num)) {
            return 'Hipercard';
        } else {
            return false;
        }
    }

    public function check_mundipagg_boleto($datas) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('Payment');
        $payment_data['AmountInCents'] = $datas['AmountInCents'];
        $payment_data['DocumentNumber'] = $datas['DocumentNumber'];
        $payment_data['OrderReference'] = $datas['OrderReference'];
        $payment_data['id'] = $datas['pk'];
        $payment_data['name'] = $datas['name'];
        $payment_data['cpf'] = $datas['cpf'];
        $payment_data['cep'] = $datas['cep'];
        $payment_data['street_address'] = $datas['street_address'];
        $payment_data['house_number'] = $datas['house_number'];
        $payment_data['neighborhood_address'] = $datas['neighborhood_address'];
        $payment_data['municipality_address'] = $datas['municipality_address'];
        $payment_data['state_address'] = $datas['state_address'];
        return $this->payment->create_boleto_payment($payment_data);
    }

    public function delete_recurrency_payment($order_key) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('Payment');
        $response = $this->payment->delete_payment($order_key);
        return $response;
    }

    public function unfollow_total() {
        $this->is_ip_hacker();
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $datas['unfollow_total'] = (int) $datas['unfollow_total'];
            ($datas['unfollow_total'] == 0) ? $ut = 'DISABLED' : $ut = 'ACTIVATED';
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'), 'TOTAL UNFOLLOW ' . $ut);
            $this->client_model->update_client($this->session->userdata('id'), array(
                'unfollow_total' => $datas['unfollow_total']
            ));
            $response['success'] = true;
            $response['unfollow_total'] = $datas['unfollow_total'];
        }
        echo json_encode($response);
    }

    public function autolike() {
        $this->is_ip_hacker();
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $al = (int) $datas['autolike'];
            $this->client_model->update_client($this->session->userdata('id'), array(
                'like_first' => $al
            ));
            ($al == 0) ? $ut = 'DISABLED' : $ut = 'ACTIVATED';
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'), 'AUTOLIKE ' . $ut);

            $response['success'] = true;
            $response['autolike'] = $datas['AUTOLIKE'];
        }
        echo json_encode($response);
    }

    public function play_pause() {
        $this->is_ip_hacker();
        $this->load->model('class/user_role');
        $this->load->model('class/client_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $datas = $this->input->post();
            $pp = (int) $datas['play_pause'];
            $this->client_model->update_client($this->session->userdata('id'), array(
                'paused' => $pp
            ));
            $ut = 'PAUSED';
            if ($pp == 1) {
                $ut = 'PAUSED';
                $active_profiles = $this->client_model->get_client_workable_profiles($this->session->userdata('id'));
                $N = count($active_profiles);
                //quitar trabajo si el cliente pauso la herramienta
                for ($i = 0; $i < $N; $i++) {
                    $this->client_model->delete_work_of_profile($active_profiles[$i]['id']);
                }
            } else {
                $ut = 'REACTIVATED';  //no hacer nada, el robot le pone trabajo al cliente al siguiente dia
            }
            $this->load->model('class/user_model');
            $this->user_model->insert_washdog($this->session->userdata('id'), 'TOOL ' . $ut);
            $response['success'] = true;
            $response['play_pause'] = $datas['play_pause'];
        }
        echo json_encode($response);
    }

    public function client_insert_geolocalization() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $N_geolocalization = 0;
            $is_active_profile = false;
            $is_active_geolocalization = false;
            for ($i = 0; $i < $N; $i++) {
                if ($active_profiles[$i]['type'] === '1' && $active_profiles[$i]['deleted'] === '0')
                    $N_geolocalization = $N_geolocalization + 1;
                if ($active_profiles[$i]['insta_name'] == $profile['geolocalization']) {
                    if ($active_profiles[$i]['deleted'] == false)
                        if ($active_profiles[$i]['type'] === '0')
                            $is_active_profile = true;
                        elseif ($active_profiles[$i]['type'] === '1')
                            $is_active_geolocalization = true;
                    break;
                }
            }
            if (/* !$is_active_profile && */!$is_active_geolocalization) {
                if ($N_geolocalization < $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_geolocalization($profile['geolocalization']);
                    if ($profile_datas && $profile_datas->location->pk) {
                        $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile_datas->slug, $profile_datas->location->pk, '1');
                        $result = $this->verify_profile($p, $active_profiles, $N);
                        $result['img_url'] = base_url() . 'assets/images/avatar_geolocalization_present.jpg';
                        $result['profile'] = $profile['geolocalization'];
                        $result['follows_from_profile'] = 0;
                        $result['geolocalization_pk'] = $profile_datas->location->pk;
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('@1 não é uma geolocalização do Instagram', array(0 => $profile['geolocalization']));
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você alcançou a quantidade máxima de geolocalizações ativas', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                if ($is_active_profile)
                    $result['message'] = $this->T('A geolocalização informada é um perfil ativo', array(), $GLOBALS['language']);
                else
                    $result['message'] = $this->T('A geolocalizaçao informada ja está ativa', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'GEOCALIZATION INSERTED');
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_geolocalization() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['geolocalization'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Geolocalização eliminada', array(), $GLOBALS['language']);
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'GEOCALIZATION ELIMINATED');
            }
            echo json_encode($result);
        }
    }

    public function check_insta_geolocalization($profile) {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $this->load->library('external_services');
            $datas_of_profile = $this->external_services->get_insta_geolocalization_data_from_client(json_decode($this->session->userdata('cookies')), $profile);
            if (is_object($datas_of_profile)) {
                return $datas_of_profile;
            } else {
                return NULL;
            }
        }
    }

    public function client_insert_profile() {
        $this->is_ip_hacker();
        $id = $this->session->userdata('id');
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $N_profiles = 0;
            $is_active_profile = false;
            for ($i = 0; $i < $N; $i++) {
                if ($active_profiles[$i]['type'] === '0' && $active_profiles[$i]['deleted'] === '0')
                    $N_profiles = $N_profiles + 1;
                if ($active_profiles[$i]['insta_name'] == $profile['profile']) {
                    if ($active_profiles[$i]['deleted'] == false)
                        if ($active_profiles[$i]['type'] === '0')
                            $is_active_profile = true;
                    break;
                }
            }
            if (!$is_active_profile) {
                if ($N_profiles < $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_profile_from_client($profile['profile']);
                    if ($profile_datas && $profile_datas->pk) {
                        if (!$profile_datas->is_private) {
                            $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['profile'], $profile_datas->pk, '0');
                            $result = $this->verify_profile($p, $active_profiles, $N);
                            $result['img_url'] = $profile_datas->profile_pic_url;
                            $result['profile'] = $profile['profile'];
                            $result['follows_from_profile'] = $profile_datas->follows;
                        } else {
                            $result['success'] = false;
                            $result['message'] = $this->T('O perfil @1 é um perfil privado', array(0 => $profile['profile']), $GLOBALS['language']);
                        }
                    } else {
                        $result['success'] = false;
                        $result['message'] = $this->T('Confira que o perfil @1 existe no Instagram e não tem bloqueado você', array(0 => $profile['profile']), $GLOBALS['language']);
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você alcançou a quantidade máxima de perfis ativos', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                if ($is_active_profile)
                    $result['message'] = $this->T('O perfil informado ja está ativo', array(), $GLOBALS['language']);
                else
                    $result['message'] = $this->T('O perfil informado é uma geolocalização ativa', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'REFERENCE PROFILE INSERTED');
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_profiles() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['profile'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Perfil eliminado', array(), $GLOBALS['language']);
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'REFERENCE PROFILE ELIMINATED');
            }
            echo json_encode($result);
        }
    }

    public function check_insta_profile($profile) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $data = $this->external_services->get_insta_ref_prof_data($profile);
        if (is_object($data)) {
            return $data;
        } else {
            return NULL;
        }
    }

    public function check_insta_profile_from_client($profile) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $data = $this->external_services->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')), $profile);
        if (is_object($data)) {
            return $data;
        } else
        if (is_string($data)) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function message() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $language = $this->input->get();
        if (isset($language['language']))
            $param['language'] = $language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
        $GLOBALS['language'] = $param['language'];
        $datas = $this->input->post();
        $this->load->library('external_services');
        $result = (array)$this->external_services->send_client_contact_form($datas['name'], $datas['email'], $datas['message'], $datas['company'], $datas['telf']);
        if ($result['success']) {
            $result['message'] = $this->T('Mensagem enviada, agradecemos seu contato', array(), $GLOBALS['language']);
        }
        echo json_encode($result);
    }

    public function email_success_buy_to_atendiment($username, $useremail) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $result = $this->external_services->send_new_client_payment_done($username, $useremail);
        if ($result['success'])
            return TRUE;
        return false;
    }

    public function email_success_buy_to_client($useremail, $username, $userlogin, $userpass) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $result = $this->external_services->send_client_payment_success($useremail, $username, $userlogin, $userpass);
    }

    public function validate_post_credit_card_datas($datas) {
        $this->is_ip_hacker();
        //TODO: validate emial and datas of credit card using regular expresions
        /* if (preg_match('^[0-9]{16,16}$',$datas['credit_card_number']) &&
          preg_match('^[0-9 ]{3,3}$',$datas['credit_card_cvc']) &&
          preg_match('^[A-Z ]{4,50}$',$datas['credit_card_name']) &&
          preg_match('^[0-10-9]{2,2}$',$datas['credit_card_exp_month']) &&
          preg_match('^[2-20-01-20-9]{4,4}$',$datas['credit_card_exp_year']) &&
          preg_match('^[a-zA-Z0-9\._-]+@([a-zA-Z0-9-]{2,}[.])*[a-zA-Z]{2,4}$',$datas['client_email']))
          return true;
          else
          return false; */
        return true;
    }

    public function is_insta_user($client_login, $client_pass, $force_login) {
        $this->is_ip_hacker();
        $data_insta = NULL;
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $login_data = $this->external_services->bot_login($client_login, $client_pass, $force_login);
        if (isset($login_data->json_response->status) && $login_data->json_response->status === "ok") {
            $data_insta['status'] = $login_data->json_response->status;
            if ($login_data->json_response->authenticated) {
                $data_insta['authenticated'] = true;
                $data_insta['insta_id'] = $login_data->ds_user_id;
                $user_data = $login_data = $this->external_services->get_insta_ref_prof_data_from_client($login_data, $client_login);
                if ($data_insta && isset($user_data->follower_count))
                    $data_insta['insta_followers_ini'] = $user_data->follower_count;
                else
                    $data_insta['insta_followers_ini'] = 'Access denied';
                if ($data_insta && isset($user_data->following))
                    $data_insta['insta_following'] = $user_data->following;
                else
                    $data_insta['insta_following'] = 'Access denied';
                if ($data_insta && isset($user_data->full_name))
                    $data_insta['insta_name'] = $user_data->full_name;
                else
                    $data_insta['insta_name'] = 'Access denied';
                if (is_object($login_data))
                    $data_insta['insta_login_response'] = $login_data;
                else
                    $data_insta['insta_login_response'] = NULL;
            } else {
                $data_insta['authenticated'] = false;
                $data_insta['message'] = $login_data->json_response->message;
                if ($login_data->json_response->message === "checkpoint_required") {
                    if (strpos($login_data->json_response->verify_link, 'challenge'))
                        $data_insta['verify_account_url'] = 'https://www.instagram.com' . $login_data->json_response->verify_link;
                    else
                    if (strpos($login_data->json_response->verify_link, 'integrity'))
                        $data_insta['verify_account_url'] = $login_data->json_response->verify_link;
                    else
                        $data_insta['verify_account_url'] = $login_data->json_response->verify_link;
                } else
                if ($login_data->json_response->message === "") {
                    if (isset($login_data->json_response->phone_verification_settings) && is_object($login_data->json_response->phone_verification_settings)) {
                        $data_insta['message'] = 'phone_verification_settings';
                        $data_insta['obfuscated_phone_number'] = $login_data->json_response->two_factor_info->obfuscated_phone_number;
                    } else {
                        $data_insta['message'] = 'empty_message';
                        $data_insta['cause'] = 'empty_message';
                    }
                } else
                if ($login_data->json_response->message !== "incorrect_password") {
                    $data_insta['message'] = 'unknow_message';
                    $data_insta['unknow_message'] = $login_data->json_response->message;
                }
            }
        } else {
            if (isset($login_data->json_response->status) && $login_data->json_response->status === "fail") {
                $data_insta['status'] = $login_data->json_response->status;
            } else
            if (isset($login_data->json_response->status) && $login_data->json_response->status === "") {
                ;
            }
        }
        return $data_insta;
    }

    public function log_out() {
        $this->is_ip_hacker();
        $data['user_active'] = false;
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'), 'CLOSING SESSION');
        $this->session->sess_destroy();
        header('Location: ' . base_url());
    }

    public function create_profiles_datas_to_display() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $this->load->library('external_services');
            $this->load->model('class/client_model');
            $array_profiles = array();
            $array_geolocalization = array();
            $array_hashtag = array();
            $client_active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($client_active_profiles);
            $cnt_ref_prof = 0;
            $cnt_geolocalization = 0;
            $cnt_hashtag = 0;
            if ($N > 0) {
                for ($i = 0; $i < $N; $i++) {
                    $name_profile = $client_active_profiles[$i]['insta_name'];
                    $id_profile = $client_active_profiles[$i]['id'];
                    if ($client_active_profiles[$i]['type'] === '0') { //es un perfil de referencia
                        $datas_of_profile = $this->external_services->get_insta_ref_prof_data_from_client(json_decode($this->session->userdata('cookies')), $name_profile, $id_profile);
                        if ($datas_of_profile != NULL) {
                            $array_profiles[$cnt_ref_prof]['login_profile'] = $name_profile;
                            $array_profiles[$cnt_ref_prof]['follows_from_profile'] = $datas_of_profile->follows;
                            if (!$datas_of_profile) { //perfil existia pero fue eliminado de IG
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'deleted';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = base_url() . 'assets/images/profile_deleted.jpg';
                            } else
                            if ($client_active_profiles[$i]['end_date']) { //perfil
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'ended';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = $datas_of_profile->profile_pic_url;
                            } else
                            if ($datas_of_profile->is_private) { //perfil paso a ser privado
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'privated';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = base_url() . 'assets/images/profile_privated.jpg';
                            } else {
                                $array_profiles[$cnt_ref_prof]['status_profile'] = 'active';
                                $array_profiles[$cnt_ref_prof]['img_profile'] = $datas_of_profile->profile_pic_url;
                            }
                            $cnt_ref_prof = $cnt_ref_prof + 1;
                        } else {
                            $array_profiles[$cnt_ref_prof]['status_profile'] = 'blocked';
                            $array_profiles[$cnt_ref_prof]['img_profile'] = base_url() . 'assets/images/profile_privated.jpg';
                            $array_profiles[$cnt_ref_prof]['login_profile'] = $name_profile;
                            $array_profiles[$cnt_ref_prof]['follows_from_profile'] = '-+-';
                            $cnt_ref_prof = $cnt_ref_prof + 1;
                        }
                    } else if ($client_active_profiles[$i]['type'] === '1') { //es una geolocalizacion      
                        $datas_of_profile = $this->external_services->get_insta_geolocalization_data_from_client(json_decode($this->session->userdata('cookies')), $name_profile, $id_profile);
                        $array_geolocalization[$cnt_geolocalization]['login_geolocalization'] = $name_profile;
                        $array_geolocalization[$cnt_geolocalization]['geolocalization_pk'] = $client_active_profiles[$i]['insta_id'];
                        if ($datas_of_profile)
                            $array_geolocalization[$cnt_geolocalization]['follows_from_geolocalization'] = $datas_of_profile->follows;
                        $array_geolocalization[$cnt_geolocalization]['img_geolocalization'] = base_url() . 'assets/images/avatar_geolocalization_present.jpg';
                        if (!$datas_of_profile) {
                            $array_geolocalization[$cnt_geolocalization]['img_geolocalization'] = base_url() . 'assets/images/avatar_geolocalization_deleted.jpg';
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'deleted';
                        } else
                        if ($client_active_profiles[$i]['end_date']) { //perfil
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'ended';
                        } else {
                            $array_geolocalization[$cnt_geolocalization]['status_geolocalization'] = 'active';
                        }
                        $cnt_geolocalization = $cnt_geolocalization + 1;
                    } else { //es un hashtag    
                        $datas_of_profile = $this->external_services->get_insta_tag_data_from_client(json_decode($this->session->userdata('cookies')), $name_profile, $id_profile);

                        $array_hashtag[$cnt_hashtag]['login_hashtag'] = $name_profile;
                        $array_hashtag[$cnt_hashtag]['hashtag_pk'] = $client_active_profiles[$i]['insta_id'];
                        if ($datas_of_profile)
                            $array_hashtag[$cnt_hashtag]['follows_from_hashtag'] = $datas_of_profile->follows;
                        $array_hashtag[$cnt_hashtag]['img_hashtag'] = base_url() . 'assets/images/avatar_hashtag_present.png';
                        if (!$datas_of_profile) {
                            $array_hashtag[$cnt_hashtag]['img_hashtag'] = base_url() . 'assets/images/avatar_hashtag_deleted.png';
                            $array_hashtag[$cnt_hashtag]['status_hashtag'] = 'deleted';
                        } else
                        if ($client_active_profiles[$i]['end_date']) { //perfil
                            $array_hashtag[$cnt_hashtag]['status_hashtag'] = 'ended';
                        } else {
                            $array_hashtag[$cnt_hashtag]['status_hashtag'] = 'active';
                        }
                        $cnt_hashtag = $cnt_hashtag + 1;
                    }
                }
                if ($cnt_ref_prof)
                    $response['array_profiles'] = $array_profiles;
                else
                    $response['array_profiles'] = array();
                $response['N'] = $cnt_ref_prof;
                if ($cnt_geolocalization)
                    $response['array_geolocalization'] = $array_geolocalization;
                else
                    $response['array_geolocalization'] = array();
                $response['N_geolocalization'] = $cnt_geolocalization;
                if ($cnt_hashtag)
                    $response['array_hashtag'] = $array_hashtag;
                else
                    $response['array_hashtag'] = array();
                $response['N_hashtag'] = $cnt_hashtag;
                $response['message'] = 'Profiles loaded';
            } else {
                $response['N'] = 0;
                $response['N_geolocalization'] = 0;
                $response['N_hashtag'] = 0;
                $response['array_profiles'] = NULL;
                $response['array_geolocalization'] = NULL;
                $response['array_hashtag'] = NULL;
                $response['message'] = 'Profiles unloaded';
            }
            return json_encode($response);
        } else {
            $this->display_access_error();
        }
    }

    public function dicas_geoloc() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'), 'LOOKING AT GEOCALIZATION TIPS');
        $this->load->view('dicas_geoloc', $param);
    }

    public function help() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $language = $this->input->get();
        if (isset($language['language']))
            $param['language'] = $language['language'];
        else
            $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->view('Dicas', $param);
    }

    public function FAQ_function($language) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $result['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
        $language = $this->input->get();
        if (isset($language['language']))
            $result['language'] = $language['language'];
        else
            $result['language'] = $GLOBALS['sistem_config']->LANGUAGE;
        $this->load->model('class/client_model');
        $cuestions = $this->client_model->geting_FAQ($result);
        $this->load->model('class/user_model');
        $this->user_model->insert_washdog($this->session->userdata('id'), 'LOOKING AT FAQ');
        $result['info'] = $cuestions;
        $this->load->view('FAQ', $result);
    }

    public function create_profiles_datas_to_display_as_json() {
        $this->is_ip_hacker();
        echo($this->create_profiles_datas_to_display());
    }

    public function display_access_error() {
        $this->is_ip_hacker();
        $this->session->sess_destroy();
        header('Location: ' . base_url() . 'index.php/welcome/');
    }

    public function client_acept_discont() {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $values = $this->client_model->get_plane($this->session->userdata('plane_id'))[0];
        $value = $values['normal_val'];
        $sql = "SELECT * FROM clients WHERE clients.user_id='" . $this->session->userdata('id') . "'";
        $client = $this->user_model->execute_sql_query($sql);
        $recurrency_order_key = $client[0]['order_key'];
        $result['success'] = true;
        echo json_encode($result);
    }
    
    public function admin_making_client_login() {
        $this->is_ip_hacker();
        $datas = $this->input->get();
        $datas['user_pass'] = urldecode($datas['user_pass']);
        $result = $this->user_do_login($datas);
        if ($result['authenticated'] === true) {
            $this->client();
        } else
            echo 'Esse cliente deve ter senha errada ou mudou suas credenciais no IG';
    }

    public function T($token, $array_params = NULL, $lang = NULL) {
        $this->is_ip_hacker();
        if (!$lang) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $lang = $param['language'];
        }
        $this->load->model('class/translation_model');
        $text = $this->translation_model->get_text_by_token($token, $lang);
        $N = count($array_params);
        for ($i = 0; $i < $N; $i++) {
            $text = str_replace('@' . ($i + 1), $array_params[$i], $text);
        }
        return $text;
    }

    public function scielo_view() {
        $this->is_ip_hacker();
        $this->load->view('scielo');
    }

    public function scielo() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $datas = $this->input->post();
        $datas['amount_in_cents'] = 100;
        $resp = $this->check_mundipagg_credit_card($datas);
        if (is_object($resp) && $resp->isSuccess()) {
            $order_key = $resp->getData()->OrderResult->OrderKey;
            $response['success'] = true;
            $response['message'] = "Compra relizada com sucesso! Chave da compra na mundipagg: $order_key";
        } else if (is_object($resp)) {
            $order_key = $resp->getData()->OrderResult->OrderKey;
            $response['success'] = false;
            $response['message'] = "Compra recusada! Chave da compra na mundipagg: $order_key";
        } else {
            $response['success'] = false;
            $response['message'] = "Compra recusada!";
        }
        echo json_encode($response);
    }

    public function get_daily_report($id) {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/user_model');
            $sql = "SELECT * FROM daily_report WHERE followings != '0' AND followers != '0' AND client_id=" . $id . " ORDER BY date ASC;";  // LIMIT 30
            $result = $this->user_model->execute_sql_query($sql);
            $followings = array();
            $followers = array();
            $N = count($result);
            for ($i = 0; $i < $N; $i++) {
                if (isset($result[$i]['date'])) {
                    $dd = date("j", $result[$i]['date']);
                    $mm = date("n", $result[$i]['date']);
                    $yy = date("Y", $result[$i]['date']);
                    $followings[$i] = (object) array('x' => ($i + 1), 'y' => intval($result[$i]['followings']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
                    $followers[$i] = (object) array('x' => ($i + 1), 'y' => intval($result[$i]['followers']), "yy" => $yy, "mm" => $mm, "dd" => $dd);
                }
            }
            $response = array(
                'followings' => json_encode($followings),
                'followers' => json_encode($followers)
            );
            return $response;
        }
    }

    public function get_img_profile($profile) {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $datas = $this->check_insta_profile($profile);
        if ($datas)
            return $datas->profile_pic_url;
        else
            return 'missing_profile';
    }

    public function client_black_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/client_model');
            try {
                $bl = $this->client_model->get_client_black_or_white_list_by_id($this->session->userdata('id'), 0);
                $dados = array();
                $N = count($bl);
                for ($i = 0; $i < $N; $i++) {
                    $dados[$i] = (object) array('profile' => $bl[$i]['profile'], 'url_foto' => $this->get_img_profile($bl[$i]['profile']));
                }
                $response['client_black_list'] = $dados;
                $response['success'] = true;
                $response['cnt'] = $N;
            } catch (Exception $ex) {
                $response['success'] = false;
            }
            echo json_encode($response);
        }
    }

    public function insert_profile_in_black_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];

            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            $datas = $this->check_insta_profile($profile);
            if ($datas) {
                $resp = $this->client_model->insert_in_black_or_white_list_model($this->session->userdata('id'), $datas->pk, $profile, 0);
                if ($resp['success']) {
                    $result['success'] = true;
                    $result['url_foto'] = $datas->profile_pic_url;
                    $this->load->model('class/user_model');
                    //$this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE '.$profile.'IN BLACK LIST');
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'INSERTING PROFILE IN BLACK LIST');
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('O perfil ' . $resp['message'], array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('O perfil não existe no Instagram', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }

    public function delete_client_from_black_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];

            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            if ($this->client_model->delete_in_black_or_white_list_model($this->session->userdata('id'), $profile, 0)) {
                $result['success'] = true;
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE '.$profile.' IN BLACK LIST');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'DELETING PROFILE IN BLACK LIST');
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro eliminando da lista negra', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }

    public function client_white_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/client_model');
            try {
                $bl = $this->client_model->get_client_black_or_white_list_by_id($this->session->userdata('id'), 1);
                $dados = array();
                $N = count($bl);
                for ($i = 0; $i < $N; $i++) {
                    $dados[$i] = (object) array('profile' => $bl[$i]['profile'], 'url_foto' => $this->get_img_profile($bl[$i]['profile']));
                }
                $response['client_white_list'] = $dados;
                $response['success'] = true;
                $response['cnt'] = $N;
            } catch (Exception $ex) {
                $response['success'] = false;
            }
            echo json_encode($response);
        }
    }

    public function insert_profile_in_white_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            $datas = $this->check_insta_profile($profile);
            if ($datas) {
                $resp = $this->client_model->insert_in_black_or_white_list_model($this->session->userdata('id'), $datas->pk, $profile, 1);
                if ($resp['success']) {
                    $result['success'] = true;
                    $result['url_foto'] = $datas->profile_pic_url;
                    $this->load->model('class/user_model');
                    //$this->user_model->insert_washdog($this->session->userdata('id'),'INSERTING PROFILE '.$profile.'IN WHITE LIST ');
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'INSERTING PROFILE IN WHITE LIST');
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('O perfil ' . $resp['message'], array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('O perfil não existe no Instagram', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }

    public function delete_client_from_white_list() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post()['profile'];
            if ($this->client_model->delete_in_black_or_white_list_model($this->session->userdata('id'), $profile, 1)) {
                $result['success'] = true;
                $this->load->model('class/user_model');
                //$this->user_model->insert_washdog($this->session->userdata('id'),'DELETING PROFILE '.$profile.' IN WHITE LIST');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'DELETING PROFILE IN WHITE LIST');
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro eliminando da lista negra', array(), $GLOBALS['language']);
            }
            echo json_encode($result);
        }
    }

    public function security_code_request() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $this->load->model('class/user_role');
        $this->load->model('class/user_model');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            try {
                $checkpoint_data = $this->external_services->checkpoint_requested($this->session->userdata('login'), $this->session->userdata('pass'));
            } catch (Exception $ex) {
                $result['success'] = false;
                $result['message'] = $this->T('Erro ao solicitar código de segurança', array(), $this->session->userdata('language'));
                $this->user_model->insert_washdog($this->session->userdata('id'), 'ERROR #4 IN SECURITY CODE REQUEST');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'Exception message: ' . $ex->getMessage());
                $this->user_model->insert_washdog($this->session->userdata('id'), 'Exception stack trace: ' . $ex->getTraceAsString());
                echo json_encode($result);
                return;
            }
            if ($checkpoint_data && $checkpoint_data->status == "ok") {
                if ($checkpoint_data->type == "CHALLENGE") {
                    $result['success'] = true;
                    $result['message'] = $this->T('Código de segurança solicitado corretamente', array(), $this->session->userdata('language'));
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'SECURITY CODE REQUESTED');
                } else if ($checkpoint_data->type == "CHALLENGE_REDIRECTION") {
                    $result['success'] = false;
                    $result['message'] = $this->T('Por favor, entre no seu Instagram e confirme FUI EU. Depois saia do seu Instagram e volte ao Passo 1 nesta página.', array(), $this->session->userdata('language'));
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'ERROR #1 IN SECURITY CODE REQUEST');
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Erro ao solicitar código de segurança', array(), $this->session->userdata('language'));
                    $this->user_model->insert_washdog($this->session->userdata('id'), 'ERROR #2 IN SECURITY CODE REQUEST');
                }
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro ao solicitar código de segurança', array(), $this->session->userdata('language'));
                $this->user_model->insert_washdog($this->session->userdata('id'), 'ERROR #3 IN SECURITY CODE REQUEST');
            }
            echo json_encode($result);
        } else {
            $this->display_access_error();
        }
    }

    public function security_code_confirmation() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $this->load->model('class/user_role');
        if ($this->session->userdata('role_id') == user_role::CLIENT) {
            $security_code = $this->input->post()['security_code'];
            $checkpoint_data = $this->external_services->make_checkpoint($this->session->userdata('login'), $security_code);
            $this->load->model('class/user_model');
            if ($checkpoint_data && $checkpoint_data->json_response->status === 'ok' && $checkpoint_data->sessionid !== null && $checkpoint_data->ds_user_id !== null) {
                $result['success'] = true;
                $result['message'] = 'Código de segurança confirmado corretamente';
                $this->user_model->insert_washdog($this->session->userdata('id'), 'SECURITY CODE CONFIRMATED');
            } else {
                $result['success'] = false;
                $result['message'] = 'Erro ao confirmar código de segurança';
                $this->user_model->insert_washdog($this->session->userdata('id'), 'ERROR IN SECURITY CODE CONFIRMATION');
            }
            echo json_encode($result);
        } else {
            $this->display_access_error();
        }
    }

    public function client_insert_hashtag() {
        $this->is_ip_hacker();
        $id = $this->session->userdata('id');
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $this->load->model('class/user_status');
            $profile = $this->input->post();
            $active_profiles = $this->client_model->get_client_active_profiles($this->session->userdata('id'));
            $N = count($active_profiles);
            $N_profiles = 0;
            $is_active_tag = false;
            for ($i = 0; $i < $N; $i++) {
                if ($active_profiles[$i]['type'] === '2' && $active_profiles[$i]['deleted'] === '0')
                    $N_profiles = $N_profiles + 1;
                if ($active_profiles[$i]['insta_name'] == $profile['hashtag']) {
                    if ($active_profiles[$i]['deleted'] == false && $active_profiles[$i]['type'] === '2')
                        $is_active_tag = true;
                    break;
                }
            }
            if (!$is_active_tag) {
                if ($N_profiles < $GLOBALS['sistem_config']->REFERENCE_PROFILE_AMOUNT) {
                    $profile_datas = $this->check_insta_tag_from_client($profile['hashtag']);
                    if ($profile_datas) {
                        $p = $this->client_model->insert_insta_profile($this->session->userdata('id'), $profile['hashtag'], $profile_datas->id, '2');
                        $result = $this->verify_profile($p, $active_profiles, $N);
                        $result['img_url'] = base_url() . 'assets/images/avatar_hashtag_present.png';
                        ;
                        $result['profile'] = $profile['hashtag'];
                        $result['follows_from_profile'] = 0;
                    } else {
                        $result['success'] = false;
                        $result['message'] = "#" . $profile['hashtag'] . " " . $this->T('não é um hashtag do Instagram', array(), $GLOBALS['language']);
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->T('Você alcançou a quantidade máxima de perfis ativos', array(), $GLOBALS['language']);
                }
            } else {
                $result['success'] = false;
                if ($is_active_profile)
                    $result['message'] = $this->T('O perfil informado ja está ativo', array(), $GLOBALS['language']);
                else
                    $result['message'] = $this->T('O perfil informado é uma hashtag ativo', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'HASHTAG INSERTED');
            }
            echo json_encode($result);
        }
    }

    public function client_desactive_hashtag() {
        $this->is_ip_hacker();
        if ($this->session->userdata('id')) {
            $this->load->model('class/system_config');
            $GLOBALS['sistem_config'] = $this->system_config->load();
            $language = $this->input->get();
            if (isset($language['language']))
                $param['language'] = $language['language'];
            else
                $param['language'] = $GLOBALS['sistem_config']->LANGUAGE;
            $param['SERVER_NAME'] = $GLOBALS['sistem_config']->SERVER_NAME;
            $GLOBALS['language'] = $param['language'];
            $this->load->model('class/client_model');
            $profile = $this->input->post();
            if ($this->client_model->desactive_profiles($this->session->userdata('id'), $profile['hashtag'])) {
                $result['success'] = true;
                $result['message'] = $this->T('Hashtag eliminado', array(), $GLOBALS['language']);
            } else {
                $result['success'] = false;
                $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
            }
            if ($result['success'] == true) {
                $this->load->model('class/user_model');
                $this->user_model->insert_washdog($this->session->userdata('id'), 'HASHTAG ELIMINATED');
            }
            echo json_encode($result);
        }
    }

    public function check_insta_tag_from_client($profile) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->library('external_services');
        $data = $this->external_services->get_insta_tag_data_from_client(json_decode($this->session->userdata('cookies')), $profile);
        if (is_object($data)) {
            return $data;
        } else
        if (is_string($data)) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function verify_profile($profile_id, $active_profiles, $N) {
        $this->is_ip_hacker();
        if ($profile_id) {
            if ($this->session->userdata('status_id') == user_status::ACTIVE && $this->session->userdata('insta_datas'))
                $q = $this->client_model->insert_profile_in_daily_work($profile_id, $this->session->userdata('insta_datas'), $N, $active_profiles, $this->session->userdata('to_follow'));
            else
                $q = true;
            $result['success'] = true;
            if ($q) {
                $result['message'] = $this->T('Perfil adicionado corretamente', array(), $GLOBALS['language']);
            } else {
                $result['message'] = $this->T('O trabalho com o perfil começara depois', array(), $GLOBALS['language']);
            }
        } else {
            $result['success'] = false;
            $result['message'] = $this->T('Erro no sistema, tente novamente', array(), $GLOBALS['language']);
        }
        return $result;
    }

    public function check_2nd_step_activation() {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $datas = $this->input->post();
        $client_id = $this->Crypt->decodify_level1(urldecode($datas['client_id']));
        $query = $this->client_model->get_all_data_of_client($client_id);

        if (!empty($query) && $query[0]['purchase_counter'] > 0 && $query[0]['purchase_access_token'] === $datas['purchase_access_token']) {
            $result['success'] = true;
            $data_insta = $this->check_insta_profile($query[0]['login']);
            $result['datas'] = json_encode($data_insta);
        } else {
            $result['success'] = false;
        }

        echo json_encode($result);
    }

    public function validaCPF($cpf = null) {
        $this->is_ip_hacker();
        $cpf = '06266544750';
        if (empty($cpf))
            return false;
        $cpf = preg_replace('[^0-9]', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        if (strlen($cpf) != 11)
            return false;
        else if ($cpf == '00000000000' ||
                $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' ||
                $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' ||
                $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        } else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }

    public function is_ip_hacker() {
        $IP_hackers = array(
            '191.176.169.242', '138.0.85.75', '138.0.85.95', '177.235.130.16', '191.176.171.14', '200.149.30.108', '177.235.130.212', '66.85.185.69',
            '177.235.131.104', '189.92.238.28', '168.228.88.10', '201.86.36.209', '177.37.205.210', '187.66.56.220', '201.34.223.8', '187.19.167.94',
            '138.0.21.188', '168.228.84.1', '138.36.2.18', '201.35.210.135', '189.71.42.124', '138.121.232.245', '151.64.57.146', '191.17.52.46', '189.59.112.125',
            '177.33.7.122', '189.5.107.81', '186.214.241.146', '177.207.99.29', '170.246.230.138', '201.33.40.202', '191.53.19.210', '179.212.90.46', '177.79.7.202',
            '189.111.72.193', '189.76.237.61', '177.189.149.249', '179.223.247.183', '177.35.49.40', '138.94.52.120', '177.104.118.22', '191.176.171.14', '189.40.89.248',
            '189.89.31.89', '177.13.225.38', '186.213.69.159', '177.95.126.121', '189.26.218.161', '177.193.204.10', '186.194.46.21', '177.53.237.217', '138.219.200.136',
            '177.126.106.103', '179.199.73.251', '191.176.171.14', '179.187.103.14', '177.235.130.16', '177.235.130.16', '177.235.130.16', '177.47.27.207'
        );
        if (in_array($_SERVER['REMOTE_ADDR'], $IP_hackers)) {
            die('Error IP: Sua solicitação foi negada. Por favor, contate nosso atendimento');
        }
    }

    public function check_registration_code() {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $datas = $this->input->post();
        $query = $this->client_model->get_client_by_id($datas['pk']);
        $retry_registration_counter = (int) $query[0]['retry_registration_counter'];
        $result['success'] = false;

        if (!empty($query)) {
            if ($query[0]['retry_registration_counter'] > 0) {
                if ($query[0]['purchase_access_token'] === $datas['registration_code']) {
                    $result['registration_code'] = $datas['registration_code'];
                    $result['success'] = true;
                    $result['message'] = $this->T('Código do cadastro verificado corretamente!', array(), $GLOBALS['language']);
                } else {
                    // decrementar el retry_registration_counter en la base de datos
                    $retry_registration_counter = $retry_registration_counter - 1;
                    $this->client_model->update_client($datas['pk'], array('retry_registration_counter' => $retry_registration_counter));
                    $result['message'] = $this->T('Código do cadastro inválido!', array(), $GLOBALS['language']);
                }
            } else {
                $result['message'] = $this->T('Alcançou a quantidade máxima de tentativas de cadastro. Por favor, entre en contato com o atendimento.', array(), $GLOBALS['language']);
            }
        } else {
            $result['message'] = $this->T('O perfil não existe no nosso sistema.', array(), $GLOBALS['language']);
        }
        echo json_encode($result);
    }

    public function get_cep_datas() {
        $cep = $this->input->post()['cep'];
        $datas = file_get_contents('https://viacep.com.br/ws/' . $cep . '/json/');
        if (strpos($datas, 'erro') > 0) {
            $response['success'] = false;
        } else {
            $response['success'] = true;
        }
        $response['datas'] = json_decode($datas);
        echo json_encode($response);
    }
    
    //DEVELOPERS, ADD NEW FUNCTION OS SYSTEM HERE ...
    
    
    
    
    //Axiliar functions VINDI
    //-----------------------------------------------------------------------------------------------

    public function login_all_blocked_by_pass() {
        $this->load->model('class/client_model');
        $client = $this->client_model->get_all_clients_by_status_id(3);
        foreach ($client as $client) {
            $datas['user_login'] = $client['login'];
            $datas['user_pass'] = $client['pass'];
            $datas['force_login'] == false;
            $result = $this->user_do_login($datas);
            if ($result['authenticated'])
                echo $client['login'] . 'authenticated and it is in ACTIVE status<br>';
            else
                echo $client['login'] . 'NOT authenticated by ' . $result['cause'] . 'cause<br>';
        }
    }

    public function tio_patinhas_vindi() {
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/Crypt');
        $this->load->model('class/external_services');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        //require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/PaymentVindi.php';
        //$this->Vindi = new \follows\cls\Payment\Vindi();
        $clients = $this->client_model->get_all_clients_by_status_id(2);
        foreach ($clients as $client) {
            if ($client['plane_id'] == 1)
                $client['plane_id'] = '4';
            //1. cobrar en la hora
            $recurrency_value = $this->client_model->get_normal_pay_value($client['plane_id']);
            $amount = (int) ($recurrency_value / 100);
            try {
                $resp = $this->external_services->create_payment($client['user_id'],$GLOBALS['sistem_config']->prod_1real_id, $amount);
            } catch (Exception $exc) {
                echo 'Cliente ' . $client['user_id'] . ' não foi cobrado na hora por: ' . $exc->getMessage() . ' <br><br>';
            }
            if ($resp->success) {
                if ($resp->status == "paid") {
                    echo 'Cliente ' . $client['user_id'] . ' cobrado na hora satisfatórimente<br><br>';                    
                    $this->client_model->update_client(
                        $client['user_id'], array('mundi_to_vindi' => 3));
                }
            } else
                echo 'Cliente ' . $client['user_id'] . ' não foi cobrado na hora <br><br>';
        }
    }

    public function is_vindi() {
        $this->load->model('class/client_model');
        var_dump($this->client_model->is_vindi_client( 40206 ));
    }
    
    public function mundi_to_vindi() {
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $this->load->model('class/external_services');
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        //require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/PaymentVindi.php';
        //$this->Vindi = new \follows\cls\Payment\Vindi();
        $sts = array(2,10,3,1); //6,5,9
        $clients = $this->client_model->get_all_clients_by_status_id(10);
        foreach ($clients as $client) {
            if(!$this->client_model->is_vindi_client($this->session->userdata('id'))){                
                $datas['user_email'] = $client['email'];
                $datas['credit_card_number'] = $this->Crypt->decodify_level1($client['credit_card_number']);
                $datas['credit_card_cvc'] = $this->Crypt->decodify_level1($client['credit_card_cvc']);
                $datas['credit_card_name'] = $client['credit_card_name'];
                $datas['credit_card_exp_month'] = $client['credit_card_exp_month'];
                $datas['credit_card_exp_year'] = $client['credit_card_exp_year'];
                $datas['pay_day'] = $this->get_pay_day($client['pay_day'])['pay_day'] ;
                if ($datas['credit_card_name'] !== 'PAYMENT_BY_TICKET_BANK' && $datas['credit_card_name'] != '' && $datas['credit_card_number'] != '' && $datas['credit_card_cvc'] != '' && $datas['credit_card_exp_month'] != '' && $datas['credit_card_exp_year'] != '') {
                    //1. crear cliente en la vindi
                    try {
                        $gateway_client_id = $this->external_services->addClient($datas['credit_card_name'], $datas['user_email']);
                    } catch (Exception $exc) {
                        $gateway_client_id = FALSE;
                        echo "Cliente " . $client['user_id'] . "no pudo ser cadastrado en la Vindi por" . $exc->getMessage() . "<br><br>";
                    }
                    if (!$gateway_client_id)
                        echo "Cliente " . $client['user_id'] . "no pudo ser cadastrado en la Vindi " . "<br><br>";
                    else {
                        //2. insertar datos del pagamneto en el sistema                    
                        $this->client_model->set_client_payment(
                                $client['user_id'], $gateway_client_id, $client['plane_id']);
                        //3. crear carton en la vindi
                        try {
                            $resp1 = $this->external_services->addClientPayment($client['user_id'], $datas);
                        } catch (Exception $exc) {
                            $resp1 = false;
                            echo "Cliente " . $client['user_id'] . "no pudo ser creado o cartão de crédito por: " . $exc->getMessage() . "<br><br>";
                        }
                        if (!$resp1->success)
                            echo "Cliente " . $client['user_id'] . "no pudo ser creado o cartão de crédito por: " . $resp2->message . "<br><br>";
                        else {
                            //4. crear recurrencia segun plano-producto
                            try {
                                $resp2 = $this->external_services->create_recurrency_payment($client['user_id'], $datas['pay_day'], $client['plane_id']);
                            } catch (Exception $exc) {
                                $resp2 = FALSE;
                                echo "Cliente " . $client['user_id'] . " no pudo ser creada la recurrencia por: " . $exc->getMessage() . "<br><br>";
                            }
                            if (!$resp2->success)
                                echo "Cliente " . $client['user_id'] . " no pudo ser creada la recurrencia por: " . $resp2->message . "<br><br>";
                            else {
                                //5. salvar order_key (payment_key)
                                $this->client_model->update_client_payment(
                                    $client['user_id'],
                                    array(
                                        'payment_key' => $resp2->payment_key,                                            
                                    ));
                                echo "Cliente: " . $client['user_id'] . " creada recurrencia bien. <br><br>";
                                
                                if (date('d/m/Y', $datas['pay_day']) == date('d/m/Y', time()))
                                    echo "analisar si fue cobrado en la mundi y en la Vindi hoje <br><br>";
                                $this->delete_recurrency_payment($client['order_key']);
                                if($client['pay_day'])
                                    $pay_day = $this->get_pay_day($client['pay_day'])['pay_day'];
                                else
                                    $pay_day = $this->get_pay_day(strtotime("+2 days",$client['init_date']))['pay_day'];
                                //6. actualizar mundi_to_vindi en la base de datos
                                $this->client_model->update_client(
                                    $client['user_id'], 
                                    array(
                                        'mundi_to_vindi' => 1,
                                        'pay_day' => $pay_day
                                   ));
                            }
                        }
                    }
                } else
                    echo "<br>Cliente " . $client['user_id'] . " com cartão inválido: <br><br>";
            }
            die();
        }
    }
    
    
    //Axiliar functions MUNDI
    //-----------------------------------------------------------------------------------------------
    public function buy_retry_for_clients_with_puchase_counter_in_zero() {
        $this->is_ip_hacker();
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $cl = $this->client_model->beginners_with_purchase_counter_less_value(9);
        for ($i = 1; $i < count($cl); $i++) {
            $clients = $cl[$i];
            $datas = array('client_login' => $clients['login'],
                'client_pass' => $clients['pass'],
                'client_email' => $clients['email']);
            $resp = $this->check_user_for_sing_in($datas);
            if ($resp['success']) {
                $datas = array(
                    'pk' => $clients['user_id'],
                    'credit_card_number' => $this->Crypt->decodify_level1($clients['credit_card_number']),
                    'credit_card_cvc' => $this->Crypt->decodify_level1($clients['credit_card_cvc']),
                    'credit_card_name' => $clients['credit_card_name'],
                    'credit_card_exp_month' => $clients['credit_card_exp_month'],
                    'credit_card_exp_year' => $clients['credit_card_exp_year'],
                    'plane_type' => $clients['plane_id'],
                    'ticket_peixe_urbano' => $clients['ticket_peixe_urbano'],
                    'user_email' => $clients['email'],
                    'insta_name' => $clients['name'],
                    'user_login' => $clients['login'],
                    'user_pass' => $clients['pass'],
                );
                $resp = $this->check_client_data_bank($datas);
                if ($resp['success']) {
                    echo 'Cliente (' . $clients['login'] . ')   ' . $clients['login'] . 'comprou satisfatoriamente\n<br>';
                } else {
                    $this->client_model->update_client($clients['user_id'], array(
                        'purchase_counter' => -100));
                    echo 'Cliente ' . $clients['login'] . ' ERRADO\n<br>';
                }
            } else {
                $this->client_model->update_client($clients['user_id'], array(
                    'purchase_counter' => -100));
                echo 'Cliente (' . $clients['login'] . ') ' . $clients['login'] . 'não passou passo 1\n<br>';
            }
        }
    }

    public function update_client_after_retry_payment_success($user_id) {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/Crypt');
        //1. recuperar el cliente y su plano
        $client = $this->client_model->get_all_data_of_client($user_id)[0];
        $plane = $this->client_model->get_plane($client['plane_id'])[0];
        //3. crear nueva recurrencia en la Mundipagg para el proximo mes   
        date_default_timezone_set('Etc/UTC');
        $payment_data['credit_card_number'] = $this->Crypt->decodify_level1($client['credit_card_number']);
        $payment_data['credit_card_name'] = $client['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $this->Crypt->decodify_level1($client['credit_card_cvc']);
        if ($client['actual_payment_value'] != '' && $client['actual_payment_value'] != null)
            $payment_data['amount_in_cents'] = $client['actual_payment_value'];
        else
            $payment_data['amount_in_cents'] = $plane['normal_val'];
        $payment_data['pay_day'] = strtotime("+1 month", time());
        $resp = $this->check_recurrency_mundipagg_credit_card($payment_data, 0);
        //4. salvar nuevos pay_day e order_key
        if (is_object($resp) && $resp->isSuccess()) {
            //2. eliminar recurrencia actual en la Mundipagg
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => $resp->getData()->OrderResult->OrderKey,
                'pay_day' => $payment_data['pay_day']));
            echo '<br>Client ' . $user_id . ' updated correctly. New order key is:  ' . $resp->getData()->OrderResult->OrderKey;
            //5. actualizar status del cliente
            $data_insta = $this->is_insta_user($client['login'], $client['pass']);
            if ($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::ACTIVE
                ));
                echo ' STATUS = ' . user_status::ACTIVE;
            } else
            if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']) {
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = ' . user_status::BLOCKED_BY_INSTA;
            } else {
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = ' . user_status::VERIFY_ACCOUNT;
            }
        } else {
            $this->user_model->update_user($user_id, array(
                'status_date' => time(),
                'status_id' => 1));
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => '',
                'observation' => 'NÃO CONSEGUIDO DURANTE RETENTATIVA - TENTAR CRIAR ANTES DE DATA DE PAGAMENTO',
                'pay_day' => $payment_data['pay_day']));
            //TO-DO:Ruslan: inserta una pendencia automatica aqui

            if (is_object($resp))
                echo '<br>Client ' . $user_id . ' DONT updated. Wrong order key is:  ' . $resp->getData()->OrderResult->OrderKey;
            else
                echo '<br>Client ' . $user_id . ' DONT updated. Missing order key';
        }

        $this->client_model->update_client($user_id, array(
            'initial_order_key' => ''));
    }
    
    public function capturer_and_recurrency_for_blocked_by_payment() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/Crypt');
        $params = $this->input->get();
        $result = $this->client_model->get_all_clients_by_status_id(2);
        foreach ($result as $client) {
            $aa = $client['login'];
            echo "<br><br>Client " . $aa . " in turn and has " . $client['retry_payment_counter'] . " paymnets retry<br><br>";
            $status_id = $client['status_id'];
            if ($client['retry_payment_counter'] <= 7) {
                if ($client['credit_card_number'] != null && $client['credit_card_number'] != null &&
                        $client['credit_card_name'] != null && $client['credit_card_name'] != '' &&
                        $client['credit_card_exp_month'] != null && $client['credit_card_exp_month'] != '' &&
                        $client['credit_card_exp_year'] != null && $client['credit_card_exp_year'] != '' &&
                        $client['credit_card_cvc'] != null && $client['credit_card_cvc'] != '') {

                    $pay_day = time();
                    $payment_data['credit_card_number'] = $this->Crypt->decodify_level1($client['credit_card_number']);
                    $payment_data['credit_card_name'] = $client['credit_card_name'];
                    $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
                    $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
                    $payment_data['credit_card_cvc'] = $this->Crypt->decodify_level1($client['credit_card_cvc']);


                    $difference = $pay_day - $client['init_date'];
                    $second = 1;
                    $minute = 60 * $second;
                    $hour = 60 * $minute;
                    $day = 24 * $hour;
                    $num_days = floor($difference / $day);

                    $payment_data['amount_in_cents'] = 0;
                    if ($client['ticket_peixe_urbano'] === 'AMIGOSDOPEDRO' || $client['ticket_peixe_urbano'] === 'INSTA15D') {
                        $payment_data['amount_in_cents'] = $this->client_model->get_normal_pay_value($client['plane_id']);
                    } else
                    if (($client['ticket_peixe_urbano'] === 'INSTA50P' ||
                            $client['ticket_peixe_urbano'] === 'BACKTODUMBU' ||
                            $client['ticket_peixe_urbano'] === 'BACKTODUMBU-DNLO' ||
                            $client['ticket_peixe_urbano'] === 'BACKTODUMBU-EGBTO')) {
                        $payment_data['amount_in_cents'] = $this->client_model->get_normal_pay_value($client['plane_id']);
                        if ($num_days <= 33)
                            $payment_data['amount_in_cents'] = $payment_data['amount_in_cents'] / 2;
                    } else
                    if ($client['ticket_peixe_urbano'] === 'DUMBUDF20') {
                        $payment_data['amount_in_cents'] = $this->client_model->get_normal_pay_value($client['plane_id']);
                        $payment_data['amount_in_cents'] = ($payment_data['amount_in_cents'] * 8) / 10;
                    } else
                    if ($client['ticket_peixe_urbano'] === 'INSTA-DIRECT' || $client['ticket_peixe_urbano'] === 'MALADIRETA') {
                        $payment_data['amount_in_cents'] = $this->client_model->get_normal_pay_value($client['plane_id']);
                    } else
                    if ($client['actual_payment_value'] != null &&
                            $client['actual_payment_value'] != 'null' &&
                            $client['actual_payment_value'] != '' &&
                            $client['actual_payment_value'] != NULL && $payment_data['amount_in_cents'] == 0
                    )
                        $payment_data['amount_in_cents'] = $client['actual_payment_value'];
                    else
                        $payment_data['amount_in_cents'] = $this->client_model->get_normal_pay_value($client['plane_id']);

                    $resp = $this->check_mundipagg_credit_card($payment_data);
                    if ((is_object($resp) && $resp->isSuccess() && $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents > 0)) {
                        $this->update_client_after_retry_payment_success($client['user_id']);
                        $this->client_model->update_client($client['user_id'], array(
                            'retry_payment_counter' => 0));
                        echo "<br><br>Client " . $aa . " retried correctly<br><br>";
                    } else {
                        $this->client_model->update_client($client['user_id'], array(
                            'retry_payment_counter' => $client['retry_payment_counter'] + 1));
                    }
                }
            } else {
                try {
                    $this->delete_recurrency_payment($client['initial_order_key']);
                    $this->delete_recurrency_payment($client['order_key']);
                    $this->user_model->update_user($client['user_id'], array(
                        'end_date' => time(),
                        'status_date' => time(),
                        'status_id' => 4));
                    $this->client_model->update_client($client['user_id'], array(
                        'observation' => 'Cancelado automaticamente por mais te 10 retentativas de pagamento sem sucessso'));
                    echo '<br>------->Client ' . $client['user_id'] . ' cancelado por maxima de retentativas';
                } catch (Exception $e) {
                    echo 'Error deleting cliente ' . $client['user_id'] . ' in database';
                }
            }
        }
    }

    public function cancel_blocked_by_payment_by_max_retry_payment() {
        $this->is_ip_hacker();
        $this->load->model('class/system_config');
        $GLOBALS['sistem_config'] = $this->system_config->load();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $result = $this->client_model->get_all_clients_by_status_id(2);
        foreach ($result as $client) {
            if ($client['retry_payment_counter'] > 9) {
                try {
                    $this->delete_recurrency_payment($client['initial_order_key']);
                    $this->delete_recurrency_payment($client['order_key']);
                    $this->user_model->update_user($client['user_id'], array(
                        'end_date' => time(),
                        'status_date' => time(),
                        'status_id' => 4));
                    $this->client_model->update_client($client['user_id'], array(
                        'observation' => 'Cancelado automaticamente por mais de 10 retentativas de pagamento sem sucessso'));
                    echo 'Client ' . $client['user_id'] . ' cancelado por maxima de retentativas';
                } catch (Exception $e) {
                    echo 'Error deleting cliente ' . $client['user_id'] . ' in database';
                }
            }
        }
    }
    
    public function update_all_retry_clients() {
        $this->is_ip_hacker();
        $array_ids = array();
        $N = count($array_ids);
        for ($i = 0; $i < $N; $i++) {
            $this->update_client_after_retry_payment_success($array_ids[$i]);
        }
    }
    
    //Statistical functions
    //-----------------------------------------------------------------------------------------------
    public function login_all_clients() {
        $this->is_ip_hacker();
        $this->load->model('class/user_model');
        $a = $this->user_model->get_all_dummbu_clients();
        $N = count($a);
        for ($i = 0; $i < $N; $i++) {
            $st = $a[$i]['status_id'];
            if ($st !== '4' && $st !== '8' && $st !== '11' && $a[$i]['role_id'] === '2') {
                echo $i;
                $login = $a[$i]['login'];
                $pass = $a[$i]['pass'];
                $datas['user_login'] = $login;
                $datas['user_pass'] = $pass;
                $result = $this->user_do_login($datas);
            }
        }
    }

    public function time_of_live() {
        $this->is_ip_hacker();
        $this->load->model('class/user_model');
        $result = $this->user_model->time_of_live_model(4);
        $response = array(
            '0-2-dias' => array(0, 0, 0, 0, 0),
            '2-30-dias' => array(0, 0, 0, 0, 0),
            '30-60-dias' => array(0, 0, 0, 0, 0),
            '60-90-dias' => array(0, 0, 0, 0, 0),
            '90-120-dias' => array(0, 0, 0, 0, 0),
            '120-150-dias' => array(0, 0, 0, 0, 0),
            '150-180-dias' => array(0, 0, 0, 0, 0),
            '180-210-dias' => array(0, 0, 0, 0, 0),
            '210-240-dias' => array(0, 0, 0, 0, 0),
            '240-270-dias' => array(0, 0, 0, 0, 0),
            'mais-270' => array(0, 0, 0, 0, 0));

        foreach ($result as $user) {
            $difference = $user['end_date'] - $user['init_date'];
            $second = 1;
            $minute = 60 * $second;
            $hour = 60 * $minute;
            $day = 24 * $hour;

            $plane = $user['plane_id'];

            $num_days = floor($difference / $day);
            if ($num_days <= 2)
                $response['0-2-dias'][$plane] = $response['0-2-dias'][$plane] + 1;
            else
            if ($num_days > 2 && $num_days <= 30)
                $response['2-30-dias'][$plane] = $response['2-30-dias'][$plane] + 1;
            else
            if ($num_days > 30 && $num_days <= 60)
                $response['30-60-dias'][$plane] = $response['30-60-dias'][$plane] + 1;
            else
            if ($num_days > 60 && $num_days <= 90)
                $response['60-90-dias'][$plane] = $response['60-90-dias'][$plane] + 1;
            else
            if ($num_days > 90 && $num_days <= 120)
                $response['90-120-dias'][$plane] = $response['90-120-dias'][$plane] + 1;
            else
            if ($num_days > 120 && $num_days <= 150)
                $response['120-150-dias'][$plane] = $response['120-150-dias'][$plane] + 1;
            else
            if ($num_days > 150 && $num_days <= 180)
                $response['150-180-dias'][$plane] = $response['150-180-dias'][$plane] + 1;
            else
            if ($num_days > 180 && $num_days <= 210)
                $response['180-210-dias'][$plane] = $response['180-210-dias'][$plane] + 1;
            else
            if ($num_days > 210 && $num_days <= 240)
                $response['210-240-dias'][$plane] = $response['210-240-dias'][$plane] + 1;
            else
            if ($num_days > 240 && $num_days <= 270)
                $response['240-270-dias'][$plane] = $response['240-270-dias'][$plane] + 1;
            else
                $response['mais-270'][$plane] = $response['mais-270'][$plane] + 1;
        }
        var_dump($response);
    }

    public function users_by_month_and_plane() {
        $this->is_ip_hacker();
        $status = $this->input->get()['status'];
        $this->load->model('class/user_model');
        $result = $this->user_model->time_of_live_model($status);

        foreach ($result as $user) {
            $month = date("n", $user['init_date']);
            $year = date("Y", $user['init_date']);
            $cad = $month . '-' . $year . '<br>';
            $plane_id = $user['plane_id'];
            if (!isset($r[$cad][$plane_id]))
                $r[$cad][$plane_id] = 0;
            else
                $r[$cad][$plane_id] = $r[$cad][$plane_id] + 1;
        }
        var_dump($r);
    }
    
}
