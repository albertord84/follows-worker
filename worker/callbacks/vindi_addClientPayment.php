<?php
    require_once '../class/system_config.php';
    $GLOBALS['sistem_config'] = new follows\cls\system_config();
    require_once '../class/PaymentVindi.php';
    $Vindi = new \follows\cls\Payment\Vindi();
    
    $user_id = urldecode($_POST['user_id']);
    $datas = json_decode(urldecode($_POST['datas']));
    
    $result = $Vindi->addClientPayment($pk, $datas);
    echo json_encode($result);
 ?>
