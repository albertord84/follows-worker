<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/PaymentVindi.php';
    $Vindi = new \follows\cls\Payment\Vindi();
    
    $user_id = urldecode($_POST['user_id']);
    $pay_day = urldecode($_POST['pay_day']);
    $plane_type = urldecode($_POST['plane_type']);
    
    $result = $Vindi->create_recurrency_payment($user_id, $pay_day, $plane_type);
    echo json_encode($result);
 ?>
