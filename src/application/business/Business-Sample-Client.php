<?php 

//namespace business\cls;

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
      $this->id = $client_id;
      $this->ci = &get_instance(); 
      
      $this->ci->load->model('class/user_model');
      //$this->ci->load->model('class/user_model', 'entity');
    }
    
    public function make_login() {
        echo "<br>dentro de make_login()<br>";
        echo "aqui va script para login del user: ".$this->id."<br><br>";
    }
    
    public function change_status($user_id) {
        echo "dentro de change_status()<br><br>";
        echo "<u>PASO #1</u>: recuperando de la db el user con id: ".$user_id."<br>";
        print_r($this->ci->user_model->get_user_by_id($user_id));
        
        echo "<br><br><u>PASO #2</u>: cambiando el estado via MODEL para ese user: <br>";
        
    }
    
    public function get_proxy() {
        
    }
}
