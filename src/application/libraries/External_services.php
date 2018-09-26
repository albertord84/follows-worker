<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class External_services{
    
    //funções com o Instagram
    //---------------------------------------------------------------------------------------
    function bot_login($client_login, $client_pass,$force_login){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'login'=>urlencode($client_login),
            'pass'=>urlencode($client_pass),
            'force_login'=>urlencode($force_login)
        );              
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/bot_login.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);  
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        $response = json_decode($response);
        return $response;
    }
        
    function get_insta_ref_prof_data_from_client($cookies,$profile_name, $dumbu_id_profile=NULL){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'cookies'=>urlencode(json_encode($cookies)),
            'profile_name'=>urlencode($profile_name),
            'dumbu_id_profile'=>urlencode($dumbu_id_profile)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/get_insta_ref_prof_data_from_client.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);  
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function get_insta_ref_prof_data($profile_name){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'profile_name'=>urlencode($profile_name)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/get_insta_ref_prof_data.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);  
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function get_insta_geolocalization_data_from_client($cookies,$profile_name, $dumbu_id_profile=NULL){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'cookies'=>urlencode(json_encode($cookies)),
            'profile_name'=>urlencode($profile_name),
            'dumbu_id_profile'=>urlencode($dumbu_id_profile)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/get_insta_geolocalization_data_from_client.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);  
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function get_insta_tag_data_from_client($cookies,$profile_name, $dumbu_id_profile=NULL){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'cookies'=>urlencode(json_encode($cookies)),
            'profile_name'=>urlencode($profile_name),
            'dumbu_id_profile'=>urlencode($dumbu_id_profile)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/get_insta_tag_data_from_client.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);  
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function checkpoint_requested($client_login, $client_pass){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];        
        $postData = array(
            'client_login'=>urlencode($client_login),
            'client_pass'=>urlencode($client_pass)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/checkpoint_requested.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
        
    function make_checkpoint($user_login, $security_code){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'user_login'=>urlencode($user_login),
            'security_code'=>urlencode($security_code)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/make_checkpoint.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    
    //
    //------EMAILS DESDE O SRC - GMAIL------------------------------------------------------
    function send_user_to_purchase_step($useremail, $username, $instaname, $purchase_access_token){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'useremail'=>urlencode($useremail),
            'username'=>urlencode($username),
            'instaname'=>urlencode($instaname),
            'purchase_access_token'=>urlencode($purchase_access_token)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/send_user_to_purchase_step.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function send_link_ticket_bank_and_access_link($username, $useremail, $access_link, $ticket_link){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'username'=>urlencode($username),
            'useremail'=>urlencode($useremail),
            'access_link'=>urlencode($access_link),
            'ticket_link'=>urlencode($ticket_link)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/send_link_ticket_bank_and_access_link.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function send_client_contact_form($username, $useremail, $usermsg, $usercompany, $userphone){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'username'=>urlencode($username),
            'useremail'=>urlencode($useremail),
            'usermsg'=>urlencode($usermsg),
            'usercompany'=>urlencode($usercompany),
            'userphone'=>urlencode($userphone)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/send_client_contact_form.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
        
    function send_new_client_payment_done($username, $useremail, $plane = 0){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'username'=>urlencode($username),
            'useremail'=>urlencode($useremail),
            'plane'=>urlencode($plane)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/send_new_client_payment_done.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function send_client_payment_success($useremail, $username, $instaname, $instapass){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'useremail'=>urlencode($useremail),
            'username'=>urlencode($username),
            'instaname'=>urlencode($instaname),
            'instapass'=>urlencode($instapass)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/send_client_payment_success.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    
    
    
    function addClient($credit_card_name, $user_email){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'credit_card_name'=>urlencode($credit_card_name),
            'user_email'=>urlencode($user_email),
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/vindi_addClient.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function addClientPayment($user_id, $datas){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'user_id'=>urlencode($user_id),
            'datas'=>urlencode(json_encode($datas)),
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/vindi_addClientPayment.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function create_recurrency_payment($user_id, $pay_day, $plane_type){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'user_id'=>urlencode($user_id),
            'pay_day'=>urlencode(json_encode($pay_day)),
            'plane_type'=>urlencode(json_encode($plane_type))
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/vindi_create_recurrency_payment.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function create_payment($user_id, $prod_1real_id, $amount){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'user_id'=>urlencode($user_id),
            'prod_1real_id'=>urlencode($prod_1real_id),
            'amount'=>urlencode($amount)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/vindi_create_payment.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    function cancel_recurrency_payment($client_payment_key){
        $database_config = parse_ini_file(dirname(__FILE__) . "/../../../../FOLLOWS-LAYOUT.INI", true);
        $worker_server_name = $database_config['server']['worker_server_name'];
        $postData = array(
            'client_payment_key'=>urlencode($client_payment_key)
        );
        $url = "http://$worker_server_name/follows-worker/worker/callbacks/vindi_cancel_recurrency_payment.php";
        $handler = curl_init();
        curl_setopt($handler, CURLOPT_URL, $url);  
        curl_setopt($handler, CURLOPT_POST,true);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER,true);  
        curl_setopt($handler, CURLOPT_POSTFIELDS, $postData);  
        $response = curl_exec($handler);
        $info = curl_getinfo($handler);
        $string = curl_error($handler);
        curl_close($handler);
        return json_decode($response); 
    }
    
    
    
    
    
}

?> 