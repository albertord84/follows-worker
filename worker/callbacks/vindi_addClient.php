<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/PaymentVindi.php';
    $Vindi = new \follows\cls\Payment\Vindi();
    
    $credit_card_name = urldecode($_POST['credit_card_name']);
    $user_email = urldecode($_POST['user_email']);
    
    $result = $Vindi->addClient($credit_card_name, $user_email);
    echo json_encode($result);
 ?>
