<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/PaymentVindi.php';
    $Vindi = new \follows\cls\Payment\Vindi();
    
    $user_id = urldecode($_POST['user_id']);
    $prod_1real_id = urldecode($_POST['prod_1real_id']);
    $amount = urldecode($_POST['amount']);
    
    $result = $Vindi->create_recurrency_payment($user_id, $prod_1real_id, $amount);
    echo json_encode($result);
 ?>
