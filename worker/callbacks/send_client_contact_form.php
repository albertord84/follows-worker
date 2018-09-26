<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/Gmail.php';
    $Gmail = new follows\cls\Gmail();
    
    $username = urldecode($_POST['username']);
    $useremail = urldecode($_POST['useremail']);
    $usermsg = urldecode($_POST['usermsg']);
    $usercompany = urldecode($_POST['usercompany']);
    $userphone = urldecode($_POST['userphone']); 
    
    $result = $Gmail->send_client_contact_form($username, $useremail,$usermsg,$usercompany,$userphone);
    echo json_encode($result);    
 ?>
