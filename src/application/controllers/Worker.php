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

        $a = $_POST;
        $login = urldecode($_POST['login']);
        $pass = urldecode($_POST['pass']);
        $force = urldecode($_POST['force_login']);
        //$login = "alberto_test";
        //$pass = "alberto";
        //$force = "";
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

        //    $cookies = json_decode('{"sessionid":"IGSC52abfa7163f944b76b3fc7aa6485389597d8a9e6e1e249f145604889730075ed%3AYvegHPD6EBl8vJvPUPB4Td8z6YBtwy2E%3A%7B%22_auth_user_id%22%3A3858629065%2C%22_auth_user_backend%22%3A%22accounts.backends.CaseInsensitiveModelBackend%22%2C%22_auth_user_hash%22%3A%22%22%2C%22_platform%22%3A1%2C%22_token_ver%22%3A2%2C%22_token%22%3A%223858629065%3AdbvJvAjNx1VxS5tChRYpMp7vSNtSlxHN%3A7d8e486839700c1dafdd8b10bb0884a283cccac8d6f41e6569beea87f5fa17da%22%2C%22last_refreshed%22%3A1537500468.2019324303%7D","csrftoken":"ooGbbFwK6OfIN9vgggfQOT8f1YSQ7n6H","ds_user_id":"3858629065","mid":"WwhO9wABAAHl2oOasN-evIVaU3S1","json_response":{"status":"ok","authenticated":true}}');
        //    $profile_name = "josergm86";
        //    $dumbu_id_profile = "";
        //    if($dumbu_id_profile=="")
        //        $dumbu_id_profile = NULL;
        //    $result = $Robot->get_insta_ref_prof_data_from_client($cookies, $profile_name, $dumbu_id_profile);
        //    echo json_encode($result);
        echo json_encode($profile_name);
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
        if ($dumbu_id_profile == "")
            $dumbu_id_profile = NULL;
        $result = $Robot->get_insta_tag_data_from_client($cookies, $profile_name, $dumbu_id_profile);
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

    public function checkpoint_requested_test() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Robot.php';
        $Robot = new \follows\cls\Robot();

        $client_login = 'marcosp.medina';
        $client_pass = 'Marcos*01+123';

        $result = $Robot->checkpoint_requested($client_login, $client_pass);
        echo json_encode($result);
    }

    public function do_work_by_reference_id() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Worker.php';
        $Worker = new \follows\cls\Worker();
//        $daily_work = $Worker->get_work();
        $reference_id = $_GET['reference_id'];
        if ($reference_id) {
            $daily_work = $Worker->get_work_by_id($reference_id);
            $Worker->do_follow_unfollow_work($daily_work);
        } else {
            print "Missing Refence Id...!!!";
        }
//        var_dump($daily_work);
    }

}
