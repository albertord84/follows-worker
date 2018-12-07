<?php namespace follows\cls;

/**
 * Description of ClientControler
 *
 * @author albertord
 */
class Client {
    private $id;
    private $name;
    private $insta_name;
    private $insta_pass;
    private $cookies;
    private $proxy;
    
    public function make_login() {
        //$CI
        \follows\cls\InstaAPI::Insta_Client()->do_login($insta_name, $insta_pass, $cookies, $proxy);
    }
    
    public function change_status($status_id) {
        $this->client_model->change_status();
    }
    
    public function get_proxy() {
        
    }
}
