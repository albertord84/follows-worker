<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

class Gmail extends CI_Controller {
    public function send_user_to_purchase_step() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();        
        $subject = urldecode($_POST['subject']);
        $useremail = urldecode($_POST['useremail']);
        $username = urldecode($_POST['username']);
        $instaname = urldecode($_POST['instaname']);
        $purchase_access_token = urldecode($_POST['purchase_access_token']);
        $result = $Gmail->send_user_to_purchase_step($subject, $useremail, $username, $instaname, $purchase_access_token);
        echo json_encode($result);
    }
    
    public function send_link_ticket_bank_and_access_link() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();
        $subject = urldecode($_POST['subject']);
        $useremail = urldecode($_POST['useremail']);
        $username = urldecode($_POST['username']);
        $access_link = urldecode($_POST['access_link']);
        $ticket_link = urldecode($_POST['ticket_link']);
        $result = $Gmail->send_link_ticket_bank_and_access_link($subject, $username, $useremail, $access_link, $ticket_link);
        echo json_encode($result);
    }
    
    public function send_client_contact_form() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();
        $subject = urldecode($_POST['subject']);
        $username = urldecode($_POST['username']);
        $useremail = urldecode($_POST['useremail']);
        $usermsg = urldecode($_POST['usermsg']);
        $usercompany = urldecode($_POST['usercompany']);
        $userphone = urldecode($_POST['userphone']);
        $result = $Gmail->send_client_contact_form($subject, $username, $useremail, $usermsg, $usercompany, $userphone);
        echo json_encode($result);
    }

    public function send_link_ticket_bank_in_update() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();
        $subject = urldecode($_POST['subject']);
        $username = urldecode($_POST['username']);
        $useremail = urldecode($_POST['useremail']);
        $ticket_link = urldecode($_POST['ticket_link']);
        $result = $Gmail->send_link_ticket_bank_in_update($subject, $useremail, $username, $ticket_link);
        echo json_encode($result);
    }

    public function send_client_payment_success() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();
        $subject = urldecode($_POST['subject']);
        $useremail = urldecode($_POST['useremail']);
        $username = urldecode($_POST['username']);
        $instaname = urldecode($_POST['instaname']);
        $instapass = urldecode($_POST['instapass']);
        $result = $Gmail->send_client_payment_success($subject, $useremail, $username, $instaname, $instapass, $site, $atendent_email);
        echo json_encode($result);
    }
    
    public function test_emails_robots() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        $Gmail = new follows\cls\Gmail();
        
        $useremail = 'josergm86@gmail.com';
        $username = 'José R';
        $instaname = 'josergm86';
        $instapass = 'josergm2';
        $diff_days =2;
//        
//        $useremail = 'albertord84@gmail.com';
//        $username = 'Alberto';
//        $instaname = 'alberto.dreyes';
//        $instapass = 'passs';
//        $diff_days =2;
        
//        $useremail = 'marcos.medina@zuregroup.com';
//        $username = 'Marcos';
//        $instaname = 'marcosp.medina';
//        $instapass = 'pass****';
//        $diff_days =2;

        $result = $Gmail->send_client_login_error($subject, $useremail, $username, $instaname, $instapass);
        $result = $Gmail->send_client_not_rps($subject, $useremail, $username, $instaname, $instapass);
        $result = $Gmail->send_client_payment_error($subject, $useremail, $username, $instaname, $instapass, $diff_days);
        echo json_encode($result);
    }

}
