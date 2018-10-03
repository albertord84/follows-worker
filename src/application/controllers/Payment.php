<?php

class Payment extends CI_Controller {

    // No final deste arquivo estao exemplos de posts de notificação para teste
    public function vindi_notif_post() {
        try {
            $post_str = urldecode($_POST['post_str']);
            //var_dump($post_str);
            $post = unserialize($post_str);
            $post = json_decode($post);
            //var_dump(unserialize($post_str));
            // Write the contents back to the file
            $path = __dir__ . '/../../logs/vindi/';
            $file = $path . "vindi_notif_post-" . date("d-m-Y") . ".log";
            $result = file_put_contents($file, $post_str . "\n\n", FILE_APPEND);
            //$result = file_put_contents($file, "Albert Test... I trust God!\n", FILE_APPEND);
            // Recurrence created succefully
            if (isset($post->event) && isset($post->event->type) && $post->event->type == "charge_created") {
                $gateway_client_id = $post->event->data->charge->customer->id;
                // Activate User
                //die("Activate client -> Recorrence created -> Set client pending by payment si o dia da cobrança é menor que o atual. Customer: $post->event->data->charge->customer->id");
            }
            // Bill paid succefully
            if (isset($post->event) && isset($post->event->type) && $post->event->type == "bill_paid") {
                if (isset($post->event->data) && isset($post->event->data->bill) && $post->event->data->bill->status = "paid") {
                    // Activate User
                    $gateway_client_id = $post->event->data->bill->customer->id;
                    //1. activar cliente
                    $this->load->model('class/user_model');
                    $this->load->model('class/user_status');
                    $this->load->model('class/client_model');
                    $client_id = $this->client_model->get_client_id_by_gateway_client_id($gateway_client_id);
                    if ($client_id) {
                        $this->user_model->update_user($client_id, array(
                            'status_id' => user_status::ACTIVE));
                        $result = file_put_contents($file, "$client_id: ACTIVED" . "\n\r", FILE_APPEND);
                        //2. pay_day un mes para el frente
                        $this->client_model->update_client(
                                $client_id, array('pay_day' => strtotime("+30 days", time())));
                        $result = file_put_contents($file, "$client_id: +30 pay day" . "\n\r\n\r", FILE_APPEND);
                    }
                    //die("Activate client -> Payment done!! -> Dia da cobrança um mês para frente");
                }
            }
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
            $result = file_put_contents($file, "$client_id: ". $exc->getTraceAsString() . "\n\r\n\r", FILE_APPEND);
            return;
        }

        // END COMMENT
        //        $result = file_put_contents($file, serialize($_POST['OrderStatus']), FILE_APPEND);
        if ($result === FALSE) {
            //var_dump($file);
        }
        //var_dump($file);
        echo "OK";
    }

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
//        $user_id = urldecode('30359');
//        $prod_1real_id = urldecode('231526');
//        $amount = urldecode('140');

        $result = $Vindi->create_payment($user_id, $prod_1real_id, $amount);
        echo json_encode($result);
    }

    public function vindi_create_recurrency_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/PaymentVindi.php';
        $Vindi = new \follows\cls\Payment\Vindi();

        $user_id = urldecode($_POST['user_id']);
        $pay_day = json_decode(urldecode($_POST['pay_day']));
        $plane_type = json_decode(urldecode($_POST['plane_type']));
//        $user_id = urldecode('30359');
//        $pay_day = urldecode('1539394550');
//        $plane_type = urldecode('223');
        $result = $Vindi->create_recurrency_payment($user_id, $pay_day, $plane_type);
        echo json_encode($result);
    }

}
