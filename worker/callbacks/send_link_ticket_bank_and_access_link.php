<?php
    require_once '../class/system_config.php';
    require_once '../class/Gmail.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    $Gmail = new follows\cls\Gmail();
    
    $useremail = urldecode($_POST['useremail']);
    $username = urldecode($_POST['username']);
    $access_link = urldecode($_POST['access_link']);
    $ticket_link = urldecode($_POST['ticket_link']);  
    
    $result = $Gmail->send_link_ticket_bank_and_access_link($useremail,$username,$access_link,$ticket_link);
    echo json_encode($result);    
 ?>
