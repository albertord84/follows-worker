<?php
    require_once '../class/system_config.php';
    require_once '../class/Gmail.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    $Gmail = new follows\cls\Gmail();
    
    $useremail = urldecode($_POST['useremail']);
    $username = urldecode($_POST['username']);
    $instaname = urldecode($_POST['instaname']);
    $purchase_access_token = urldecode($_POST['purchase_access_token']);  
    
    $result = $Gmail->send_user_to_purchase_step($useremail,$username,$instaname,$purchase_access_token);
    echo json_encode($result);    
 ?>
