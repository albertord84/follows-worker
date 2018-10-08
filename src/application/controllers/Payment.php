<?php

class Payment extends CI_Controller {

    // recursos a serem acessados externamente por curl
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
            $result = file_put_contents($file, "$client_id: " . $exc->getTraceAsString() . "\n\r\n\r", FILE_APPEND);
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
    
    public function mundi_delete_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Payment.php';
        $Payment = new \follows\cls\Payment();
        $order_key = urldecode($_POST['order_key']);
        $result = $Payment->delete_payment($order_key);
        echo json_encode($result);
    }
    
    
    // recursos a serem acessados internamente pelor rotos de pagamento
    public function check_payment_vindi() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        echo "Check Payment Inited...!<br>\n";
        echo date("Y-m-d h:i:sa");

        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
        $this->db->join('client_payment', 'clients.user_id = client_payment.dumbu_client_id');
        // TODO: COMENT
        //$this->db->where('id', "1");
        $this->db->where('role_id', user_role::CLIENT);
        $this->db->where('status_id <>', user_status::DELETED);
        $this->db->where('status_id <>', user_status::BEGINNER);
        $this->db->where('status_id <>', user_status::DONT_DISTURB);
        //$this->db->where('gateway_id', 1); // 1 -> Id da mundipagg
//        $this->db->where('status_id <>', user_status::BLOCKED_BY_PAYMENT);
        // TODO: COMMENT MAYBE
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_PAYMENT);  // This status change when the client update his pay data
//        $this->db->or_where('status_id', user_status::ACTIVE);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_INSTA);
//        $this->db->or_where('status_id', user_status::VERIFY_ACCOUNT);
//        $this->db->or_where('status_id', user_status::UNFOLLOW);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_TIME);
//        $this->db->or_where('status_id', user_status::INACTIVE);
//        $this->db->or_where('status_id', user_status::PENDING);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $client) {
            if ($this->is_client_vindi($client['user_id'])) { // Si é cliente da VINDI
                $clientname = $client['name'];
                $clientid = $client['user_id'];
                var_dump($clientid . ": " . $clientname);
                $now = new DateTime("now");
                //$payday = strtotime($client['pay_day']);
                $payday = new DateTime();
                $payday->setTimestamp($client['pay_day']);
                $diff_info = $payday->diff($now);
                $diff_days = $diff_info->days;
                $today = strtotime("today");
                if ($now > $payday && $client['status_id'] != user_status::BLOCKED_BY_PAYMENT) { // wheter not have order key
                    print "\n<br>Client pay data data expired!!!: $clientname (id: $clientid)<br>\n";
                    $this->send_payment_email($client, $GLOBALS['sistem_config']->DAYS_TO_BLOCK_CLIENT - $diff_days);
                    $this->load->model('class/user_status');
                    $this->user_model->update_user($clientid, array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                }
            }
        }
        try {
            $Gmail = new follows\cls\Gmail();
            $Gmail->send_mail("albertord84@gmail.com", "Alberto Reyes", 'DUMBU VINVI payment checked!!! ', 'DUMBU VINVI payment checked!!! ');
            $Gmail->send_mail("josergm86@gmail.com", "Jose Ramon ", 'DUMBU VINVI payment checked!!! ', 'DUMBU VINVI payment checked!!! ');
            $Gmail->send_mail("jangel.riveaux@gmail.com", "Jose Angel Riveaux ", 'DUMBU VINVI payment checked!!! ', 'DUMBU VINVI payment checked!!! ');
        } catch (Exception $ex) {
            echo 'Emails was not send';
        }
        echo "\n\n<br>Job Done!" . date("Y-m-d h:i:sa") . "\n\n";
    }

    public function send_payment_email($client, $diff_days = 0) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \follows\cls\system_config();
        $this->Gmail = new \follows\cls\Gmail();
        //$datas = $this->input->post();
        $result = $this->Gmail->send_client_payment_error($client['email'], $client['name'], $client['login'], $client['pass'], $diff_days);
        if ($result['success']) {
            $clientname = $client['name'];
            print "<br>Email send to client: $clientname<br>\n\r";
        } else {
            print "<br>Email NOT sent to: " . json_encode($client, JSON_PRETTY_PRINT);
//            throw new Exception("Email not sent to: " . json_encode($client));
        }
    }

    public function test_check_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        echo "Check Payment Inited...!<br>\n";
        echo date("Y-m-d h:i:sa");

        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
        // TODO: COMENT
//        $this->db->where('id', "1");
        $this->db->where('clients.user_id =', '19546');
//        $this->db->where('status_id <>', user_status::BLOCKED_BY_PAYMENT);
        // TODO: COMMENT MAYBE
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_PAYMENT);  // This status change when the client update his pay data
//        $this->db->or_where('status_id', user_status::ACTIVE);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_INSTA);
//        $this->db->or_where('status_id', user_status::VERIFY_ACCOUNT);
//        $this->db->or_where('status_id', user_status::UNFOLLOW);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_TIME);
//        $this->db->or_where('status_id', user_status::INACTIVE);
//        $this->db->or_where('status_id', user_status::PENDING);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $client) {
            $clientname = $client['name'];
            $clientid = $client['user_id'];
            $now = new DateTime("now");
            $payday = strtotime($client['pay_day']);
            $payday = new DateTime();
            $payday->setTimestamp($client['pay_day']);
            $today = strtotime("today");
//            var_dump($payday);
            if (new DateTime("now") > $payday) {
                $promotional_days = $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS;
                $init_date_2d = new DateTime();
                $init_date_2d = $init_date_2d->setTimestamp(strtotime("+$promotional_days days", $client['init_date']));
                $testing = new DateTime("now") < $init_date_2d;
                if ($client['order_key'] != NULL) { // wheter have oreder key
                    if (!$testing) { // Not in promotial days
                        try {
                            //                        var_dump($client);
                            $checked = $this->check_client_payment($client);
                        } catch (Exception $ex) {
                            $checked = FALSE;
                            //                        var_dump($ex);
                        }
                        if ($checked) {
                            //var_dump($client);
                            print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                        } else {
                            print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                        }
                    }
                } else if ($today <= $client['pay_day'] && $client['pay_day'] < strtotime("+1 day", $today) /* && $client['init_day'] */) {
                    try {
                        $checked = $this->check_initial_payment($client);
                    } catch (Exception $ex) {
                        $checked = FALSE;
                    }
                    if ($checked) {
                        //var_dump($client);
                        print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                    } else {
                        print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                    }
                } else if ($now > $payday && $client['status_id'] != user_status::BLOCKED_BY_PAYMENT) { // wheter not have order key
                    print "\n<br>Client without ORDER KEY and pay data data expired!!!: $clientname (id: $clientid)<br>\n";
                    $this->send_payment_email($client, $GLOBALS['sistem_config']->DAYS_TO_BLOCK_CLIENT - $diff_days);
                    $this->load->model('class/user_status');
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                } else {
                    print "\n<br>Client without ORDER KEY!!!: $clientname (id: $clientid)<br>\n";
                }
            }
        }
        try {
            $Gmail = new follows\cls\Gmail();
            $Gmail->send_mail("josergm86@gmail.com", "Jose Ramon ", 'DUMBU payment checked!!! ', 'DUMBU payment checked!!! ');
            $Gmail->send_mail("jangel.riveaux@gmail.com", "Jose Angel Riveaux ", 'DUMBU payment checked!!! ', 'DUMBU payment checked!!! ');
        } catch (Exception $ex) {
            echo 'Emails was not send';
        }
        echo "\n\n<br>Job Done!" . date("Y-m-d h:i:sa") . "\n\n";
    }

    public function check_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new follows\cls\system_config();
        echo "Check Payment Inited...!<br>\n";
        echo date("Y-m-d h:i:sa");

        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
        //$this->db->join('client_payment', 'clients.user_id = client_payment.dumbu_client_id');
        // TODO: COMENT
        //$this->db->where('id', "1");
        $this->db->where('role_id', user_role::CLIENT);
        $this->db->where('status_id <>', user_status::DELETED);
        $this->db->where('status_id <>', user_status::BEGINNER);
        $this->db->where('status_id <>', user_status::DONT_DISTURB);
        //$this->db->where('gateway_id', 1); // 1 -> Id da mundipagg
//        $this->db->where('status_id <>', user_status::BLOCKED_BY_PAYMENT);
        // TODO: COMMENT MAYBE
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_PAYMENT);  // This status change when the client update his pay data
//        $this->db->or_where('status_id', user_status::ACTIVE);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_INSTA);
//        $this->db->or_where('status_id', user_status::VERIFY_ACCOUNT);
//        $this->db->or_where('status_id', user_status::UNFOLLOW);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_TIME);
//        $this->db->or_where('status_id', user_status::INACTIVE);
//        $this->db->or_where('status_id', user_status::PENDING);
        $clients = $this->db->get()->result_array();
        //var_dump($clients);
        //die();
        // Check payment for each user
        foreach ($clients as $client) {
            $clientname = $client['name'];
            $clientid = $client['user_id'];
            //die($clientid . ": " . $clientname);
            if (!$this->is_client_vindi($client['user_id'])) {
                $now = new DateTime("now");
                $payday = strtotime($client['pay_day']);
                $payday = new DateTime();
                $payday->setTimestamp($client['pay_day']);
                $diff_info = $pay_day->diff($now);
                $diff_days = $diff_info->days;
                $today = strtotime("today");
                if (new DateTime("now") > $payday) {
                    $promotional_days = $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS;
                    $init_date_2d = new DateTime();
                    $init_date_2d = $init_date_2d->setTimestamp(strtotime("+$promotional_days days", $client['init_date']));
                    $testing = new DateTime("now") < $init_date_2d;
                    if ($client['order_key'] != NULL) { // wheter have oreder key
                        if (!$testing) { // Not in promotial days
                            try {
                                //                        var_dump($client);
                                $checked = $this->check_client_payment($client);
                            } catch (Exception $ex) {
                                $checked = FALSE;
                                //                        var_dump($ex);
                            }
                            if ($checked) {
                                //var_dump($client);
                                print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                            } else {
                                print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                            }
                        }
                    } else if ($today <= $payday && $payday <= strtotime("+1 day", $today)) {
                        try {
                            $checked = $this->check_initial_payment($client);
                        } catch (Exception $ex) {
                            $checked = FALSE;
                        }
                        if ($checked) {
                            //var_dump($client);
                            print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                        } else {
                            print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                        }
                    } else if ($now > $payday && $client['status_id'] != user_status::BLOCKED_BY_PAYMENT) { // wheter not have order key
                        print "\n<br>Client without ORDER KEY and pay data data expired!!!: $clientname (id: $clientid)<br>\n";
                        $this->send_payment_email($client, $GLOBALS['sistem_config']->DAYS_TO_BLOCK_CLIENT - $diff_days);
                        $this->load->model('class/user_status');
                        $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                    } else {
                        print "\n<br>Client without ORDER KEY!!!: $clientname (id: $clientid)<br>\n";
                    }
                }
            }
        }
        try {
            $Gmail = new follows\cls\Gmail();
            $Gmail->send_mail("josergm86@gmail.com", "Jose Ramon ", 'DUMBU MUNDI payment checked!!! ', 'DUMBU MUNDI payment checked!!! ');
            $Gmail->send_mail("jangel.riveaux@gmail.com", "Jose Angel Riveaux ", 'DUMBU MUNDI payment checked!!! ', 'DUMBU MUNDI payment checked!!! ');
        } catch (Exception $ex) {
            echo 'Emails was not send';
        }
        echo "\n\n<br>Job Done!" . date("Y-m-d h:i:sa") . "\n\n";
    }
    
    public function do_payment($payment_data) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \follows\cls\Payment();
        $response = $Payment->create_recurrency_payment($payment_data);
        // Save Order Key
        var_dump($response->Data->OrderResult->OrderKey);
    }

    public function is_client_vindi($client_id) {
        $client = NULL;
        try {
            $this->db->select('*');
            $this->db->from('client_payment');
            $this->db->where('dumbu_client_id', $client_id);
            $client = $this->db->get()->row_array();
        } catch (\Exception $exc) {
            return $exc;
        }
        return is_array($client) && $client["gateway_id"] == 2;
    }

    public function check_client_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/Payment.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/system_config.php';

        $this->load->model('class/System_config');
        $GLOBALS['sistem_config'] = new \follows\cls\system_config();
        // Check client payment in mundipagg
        $Payment = new \follows\cls\Payment();
        $DB = new \follows\cls\DB();
        // Check outhers payments
        $IOK_ok = $client['initial_order_key'] ? $Payment->check_client_order_paied($client['initial_order_key']) : TRUE; // Deixar para um mes de graça
        $POK_ok = $client['pending_order_key'] ? $Payment->check_client_order_paied($client['pending_order_key']) : FALSE;
        $IOK_ok = $IOK_ok || $POK_ok; // Whichever is paid
        // Check normal recurrency payment
        $result = $Payment->check_payment($client['order_key']);
        if (isset($result) && is_object($result) && $result->isSuccess()) {
            $data = $result->getData();
            //var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            $LastSaledData = NULL;
            // Get last client payment
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                $SaleDataDate = new DateTime($SaleData->DueDate);
//                $LastSaleDataDate = new DateTime($LastSaledData->DueDate);
                //$last_payed_date = DateTime($LastSaledData->DueDate);
                if ($SaleData->CapturedAmountInCents != NULL && ($LastSaledData == NULL || $SaleDataDate > new DateTime($LastSaledData->DueDate))) {
                    $LastSaledData = $SaleData;
                }
                //var_dump($SaleData);
            }
            $now = DateTime::createFromFormat('U', time());
            $this->load->model('class/user_status');
            $this->load->model('class/user_model');
            if ($LastSaledData != NULL) { // if have payment
                // Check difference between last payment and now
                $last_saled_date = new DateTime($LastSaledData->DueDate);
                $diff_info = $last_saled_date->diff($now);
                //var_dump($diff_info);
                // Diff in days
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
                print "\n<br> Diff days: $diff_days";
                // TODO: Put 34 in system_config
//                $diff_days = 35;
//                $client['email'] = 'albertord84@gmail.com';
                if ($diff_days > 34) { // Limit to bolck
                    //Block client by paiment
                    if ($client['status_id'] != user_status::BLOCKED_BY_PAYMENT) {
                        $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                        $this->send_payment_email($client, 0);
                        print "This client was blocked by payment just now: " . $client['user_id'];
                        // TODO: Put 31 in system_config    
                    }
                } elseif ($diff_days > 31) { // Limit to advice
                    // Send email to Client
                    // TODO: Think about send email
                    print "Diff in days bigger tham 31 days: $diff_days ";
                    //$this->load->model('class/system_config');
                    $this->send_payment_email($client, 34 - $diff_days + 1);
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::PENDING, 'status_date' => time()));
                } else {
//                    print_r($client);
                    if ($client['status_id'] == user_status::PENDING || $client['status_id'] == user_status::BLOCKED_BY_PAYMENT) {
                        $this->user_model->update_user($client['user_id'], array('status_id' => user_status::ACTIVE, 'status_date' => time()));
                        $DB->InsertEventToWashdog($client['user_id'], 'SET TO ATIVE', 0);
                    }
                    return TRUE;
                }
            } else if ($client['status_id'] != user_status::BLOCKED_BY_PAYMENT) { // if have not payment jet
                print "\n<br> LastSaledData = NULL";
                $pay_day = new DateTime();
                $pay_day->setTimestamp($client['pay_day']);
                $diff_info = $pay_day->diff($now);
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
                // TODO: check whend not pay and block user
                if ($now > $pay_day) {
                    print "\n<br>This client has not payment since '$diff_days' days (PROMOTIONAL?): " . $client['name'] . "<br>\n";
                    print "\n<br>Set to PENDING<br>\n";
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::PENDING, 'status_date' => time()));
                    $DB->InsertEventToWashdog($client['user_id'], 'SET TO PENDING', 0);

                    // TODO: limit email by days diff
                    //$diff_days = 6;
                    if ($diff_days >= 0) {
//                        print "\n<br>Email sent to " . $client['email'] . "<br>\n";

                        $this->send_payment_email($client, $GLOBALS['sistem_config']->DAYS_TO_BLOCK_CLIENT - $diff_days);
                        // TODO: limit email by days diff
                        if ($diff_days >= $GLOBALS['sistem_config']->DAYS_TO_BLOCK_CLIENT) {
                            //Block client by paiment
                            $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                            $DB->InsertEventToWashdog($client['user_id'], 'BLOQUED BY PAYMENT', 0);

                            ///////////////////////////////////////$this->send_payment_email($client);
                            print "This client was blocked by payment just now: " . $client['user_id'];
                            // TODO: Put 31 in system_config    
                        }
                    }
                } else if ($IOK_ok === FALSE && $diff_days >= $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS) { // Si está en fecha de promocion del mes pero no pagó initial order key
                    //Block client by paiment
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
                    $this->send_payment_email($client, 0);
                    $DB->InsertEventToWashdog($client['user_id'], 'BLOQUED BY PAYMENT', 0);

                    ///////////////////////////////////////$this->send_payment_email($client);
                    print "This client was blocked by payment just now: " . $client['user_id'];
                }
            }
            // Caso especial para activar bloqueados injustamente
            $pay_day = new DateTime();
            $pay_day->setTimestamp($client['init_date']);
            $diff_info = $pay_day->diff($now);
            $diff_days = $diff_info->days;
            if ($client['status_id'] == user_status::BLOCKED_BY_PAYMENT && ($IOK_ok === TRUE && $client['initial_order_key']) && $diff_days < 33) { // Si está en fecha de promocion del mes y initial order key
                print "\n<br> LastSaledData = NULL";
                $this->user_model->update_user($client['user_id'], array('status_id' => user_status::ACTIVE, 'status_date' => time()));
                $DB->InsertEventToWashdog($client['user_id'], 'UNBLOQUED BY PAYMENT', 0);

                print "\n<br>This client UNBLOQUED by payment just now: " . $client['user_id'];
            }
        } else {
            $bool = is_object($result);
            $str = isset($result) && is_object($result) && is_callable($result->getData()) ? json_encode($result->getData()) : "NULL";
//            throw new Exception("Payment error: " . $str);
            print ("\n<br>Payment error: " . $str . " \nClient name: " . $client['name'] . "<br>\n");
        }
        return FALSE;
//        print "<pre>";
//        print json_encode($result->getData(), JSON_PRETTY_PRINT);
//        print "</pre>";
    }

    public function retry_payment($order_key) {
        $result = $this->check_payment($order_key);
        $now = DateTime::createFromFormat('U', time());
        if (is_object($result) && $result->isSuccess()) {
            $data = $result->getData();
            //var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            $RetrySaleData = NULL;
            // Get last client payment
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                $SaleDataDate = new DateTime($SaleData->DueDate);
                if (($RetrySaleData == NULL || $SaleDataDate > new DateTime($RetrySaleData->DueDate)) && $SaleDataDate < $now) {
                    $RetrySaleData = $SaleData;
                }
            }
        }

        if ($RetrySaleData && $RetrySaleData->CapturedAmountInCents == NULL) {
            //var_dump($RetrySaleData->TransactionKey);
            $result = $this->retry_payment_recurrency($order_key, $RetrySaleData->TransactionKey);
            if (is_object($result) && $result->isSuccess()) {
                $result = $result->getData();
                $RetriedSaleData = $result->CreditCardTransactionResultCollection[0];
                if ($RetriedSaleData->CapturedAmountInCents > 100) {
                    return TRUE;
                }
            }
//        print "<pre>";
//        print json_encode($result, JSON_PRETTY_PRINT);
//        print "</pre>";
        }
        return FALSE;
    }
        
    public function check_initial_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/DB.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows/worker/class/Payment.php';

        $today = strtotime("today");
        $payment_data['credit_card_number'] = $client['credit_card_number'];
        $payment_data['credit_card_name'] = $client['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $client['credit_card_cvc'];
        $payment_data['amount_in_cents'] = $client['actual_payment_value'];
        $payment_data['pay_day'] = $client['pay_day'];

        //Verificar que tenha asignado 24 horas antes
        $payment = new \follows\cls\Payment();
        $res_pay_now = $payment->create_payment($payment_data);

        if (is_object($resp_pay_now) && $resp_pay_now->isSuccess() && $resp_pay_now->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents > 0) {
            //Tentar crear a recurrencia
            $payment_data['pay_day'] = strtotime("+1 month", $client->pay_day);
            $response = $payment->check_recurrency_mundipagg_credit_card($payment_data, 0);
            $order_key = $resp->getData()->OrderResult->OrderKey;
            $DB->SetClientOrderKey($client['user_id'], $order_key, $payment_data['pay_day']);
        }
        //Fallo en crear a arecurrencia -> notificar ao cliente e bloquear por pagamento
        else {
            $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT, 'status_date' => time()));
            $this->send_payment_email($client, 0);
            $DB->InsertEventToWashdog($client['user_id'], 'BLOQUED BY PAYMENT', 0);
        }
        //Creo a recurren ia -> continua ativo
        //if(!isset($client->))
    }

}
