<?php

ini_set('xdebug.var_display_max_depth', 256);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);



class Welcome extends CI_Controller {

    public function index() {
        var_dump("olaaaa");
        var_dump($_SERVER['DOCUMENT_ROOT']);
        require_once $_SERVER['DOCUMENT_ROOT'] . '/follows-worker/src/application/bussines/Client.php';
        $C = new bussines\cls\Client(1);
        $C->change_status(1);
    }

    public function test() {
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        $this->load->model('class/client_model');
        $gateway_client_id = 7397972;
        $client_id = $this->client_model->get_client_id_by_gateway_client_id($gateway_client_id);
        var_dump($client_id);
        $gateway_payment_key = 4875103;
        $client_id = $this->client_model->get_client_id_by_gateway_payment_key($gateway_payment_key);
        var_dump($client_id);
    }

}
