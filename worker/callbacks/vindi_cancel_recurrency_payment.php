<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/PaymentVindi.php';
    $Vindi = new \follows\cls\Payment\Vindi();
    
    $client_payment_key = urldecode($_POST['client_payment_key']);
    
    $result = $Vindi->cancel_recurrency_payment($client_payment_key);
    echo json_encode($result);
 ?>
