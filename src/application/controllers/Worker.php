<?php

class Worker extends CI_Controller {

    public function index() {
        print("ok");
    }

    public function bot_login() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $login = urldecode($_POST['login']);
        $pass = urldecode($_POST['pass']);
        $force = urldecode($_POST['force_login']);

        ($force == '') ? $force = FALSE : $force = TRUE;
        if ($login != '' && $login != FALSE && $login != NULL && $pass != '' && $pass != FALSE && $pass != NULL) {
            $result = $Robot->bot_login($login, $pass, $force);
        } else {
            $result->json_response->status = 'ok';
            $result->json_response->authenticated = FALSE;
        }
        echo json_encode($result);
    }

    public function get_insta_ref_prof_data_from_client() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $cookies = json_decode(urldecode($_POST['cookies']));
        $profile_name = urldecode($_POST['profile_name']);
        $dumbu_id_profile = urldecode($_POST['dumbu_id_profile']);
        $user_id = urldecode($_POST['user_id']);
        if ($dumbu_id_profile == "")
            $dumbu_id_profile = NULL;
        $result = $Robot->get_insta_ref_prof_data_from_client($cookies, $profile_name, $dumbu_id_profile, $user_id);
        echo json_encode($result);
    }

    public function make_checkpoint() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $user_login = urldecode($_POST['user_login']);
        $security_code = urldecode($_POST['security_code']);

        $result = $Robot->make_checkpoint($user_login, $security_code);
        //    $result = $Robot->make_checkpoint('riveauxmerino', 974215);
        echo json_encode($result);
    }

    public function get_insta_tag_data_from_client() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $cookies = json_decode(urldecode($_POST['cookies']));
        $profile_name = urldecode($_POST['profile_name']);
        $dumbu_id_profile = urldecode($_POST['dumbu_id_profile']);
        $user_id = urldecode($_POST['user_id']);

        if ($dumbu_id_profile == "")
            $dumbu_id_profile = NULL;
        $result = $Robot->get_insta_tag_data_from_client($cookies, $profile_name, $dumbu_id_profile, $user_id);
        echo json_encode($result);
    }

    public function get_insta_geolocalization_data_from_client() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $cookies = json_decode(urldecode($_POST['cookies']));
        $profile_name = urldecode($_POST['profile_name']);
        $dumbu_id_profile = urldecode($_POST['dumbu_id_profile']);
        $user_id = urldecode($_POST['user_id']);
        if ($dumbu_id_profile == "")
            $dumbu_id_profile = NULL;

        $result = $Robot->get_insta_geolocalization_data_from_client($cookies, $profile_name, $dumbu_id_profile, $user_id);
        echo json_encode($result);
    }

    public function get_insta_ref_prof_data() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();
        

        $profile_name = urldecode($_POST['profile_name']);
        if (isset($_POST['ref_prof_id']))
            $ref_prof_id = urldecode($_POST['ref_prof_id']);
        else
            $ref_prof_id = NULL;
        $result = $Robot->get_insta_ref_prof_data($profile_name, $ref_prof_id);
        echo json_encode($result);
    }

    public function checkpoint_requested() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $client_login = urldecode($_POST['client_login']);
        $client_pass = urldecode($_POST['client_pass']);

        $result = $Robot->checkpoint_requested($client_login, $client_pass);
        echo json_encode($result);
    }
    
    public function set_client_cookies_by_curl() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();
        $client_id = urldecode($_POST['client_id']);
        $curl = urldecode($_POST['curl']);
        if (isset($_POST['robot_id']))
            $robot_id = urldecode($_POST['robot_id']);
        else
            $robot_id = NULL;
        $result = $Robot->set_client_cookies_by_curl($client_id, $curl, $robot_id);
        echo json_encode($result);
    }

    public function do_work_by_reference_id() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
        $reference_id = $_GET['reference_id'];
        if ($reference_id) {
            $daily_work = $Worker->get_work_by_id($reference_id);
            $Worker->do_follow_unfollow_work($daily_work);
        } else {
            print "Missing Refence Id...!!!";
        }
    }
    
    public function get_number_followed_today() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/DB.php';
        $DB = new \follows\cls\DB();
        $client_id = $_POST['client_id'];
        $result = $DB->get_number_followed_today($client_id);
        echo json_encode($result);
    }
    

}
