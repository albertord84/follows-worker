<?php namespace bussines\cls;

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
    
    public function __construct($client_id) {
        print("\n<br> Client $client_id constructor");
        //$this->client_model->load_client($client_id);
    }
    
    public function make_login() {
        //$CI
        //\follows\cls\InstaAPI::Insta_Client()->do_login($insta_name, $insta_pass, $cookies, $proxy);
    }
    
    public function change_status($status_id) {
        print("\n<br> change status");
        $this->client_model->change_status($status_id);
    }
    
    public function get_proxy() {
        
    }
}
