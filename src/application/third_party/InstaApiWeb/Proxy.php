<?php

namespace InstaApiWeb {

  /**
   * 
   * Define
   * 
   * @category Third-Party Instagram API
   * 
   * @access public
   *.
   */
  class Proxy {

    //put your code here
    public $Id;
    public $Ip;
    public $Port;
    public $User;
    public $Password;
    public $IsReserved;

    public function __construct(string $ip, string $port, string $user, string $password) {
      $this->Ip = $ip;
      $this->Port = $port;
      $this->User = $user;
      $this->Password = $password;
    }

    public function ToString() {
      return "--proxy '$this->User:$this->Password@$this->Ip:$this->Port'";
    }
  }

}
