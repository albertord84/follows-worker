<?php

class Payment extends CI_Controller {

    public function vindi_addClient() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();

        $credit_card_name = urldecode($_POST['credit_card_name']);
        $user_email = urldecode($_POST['user_email']);

        $result = $Vindi->addClient($credit_card_name, $user_email);
        echo json_encode($result);
    }

    public function vindi_addClientPayment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();
        $user_id = urldecode($_POST['user_id']);
        $datas = (array) json_decode(urldecode($_POST['datas']));
        
//        $user_id = 30359;
//        $datas = json_decode(urldecode('%7B%22client_email%22%3A%22josergm86%40gmail.com%22%2C%22credit_card_number%22%3A%225162202091174685%22%2C%22credit_card_cvc%22%3A%22302%22%2C%22credit_card_name%22%3A%22PEDRO+BASTOS+PETTI%22%2C%22credit_card_exp_month%22%3A%2204%22%2C%22credit_card_exp_year%22%3A%222021%22%2C%22client_update_plane%22%3A%225%22%7D'));

        
        $result = $Vindi->addClientPayment($user_id, $datas);
        echo json_encode($result);
    }

    public function vindi_cancel_recurrency_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();

        $client_payment_key = urldecode($_POST['client_payment_key']);

        $result = $Vindi->cancel_recurrency_payment($client_payment_key);
        echo json_encode($result);
    }

    public function vindi_create_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();

        $user_id = urldecode($_POST['user_id']);
        $prod_1real_id = urldecode($_POST['prod_1real_id']);
        $amount = urldecode($_POST['amount']);

        $result = $Vindi->create_recurrency_payment($user_id, $prod_1real_id, $amount);
        echo json_encode($result);
    }

    public function vindi_create_recurrency_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();

        $user_id = urldecode($_POST['user_id']);
        $pay_day = urldecode($_POST['pay_day']);
        $plane_type = urldecode($_POST['plane_type']);

        $result = $Vindi->create_recurrency_payment($user_id, $pay_day, $plane_type);
        echo json_encode($result);
    }

}
