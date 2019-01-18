<?php

namespace InstaApiWeb {

  /**
   * Description of Proxy
   *
   * @author jose
   */
  class Proxy {

    //put your code here
    public $ip;
    public $port;
    public $user;
    public $password;

    public function __construct(string $ip, string $port, string $user, string $password) {
      $this->ip = $ip;
      $this->port = $port;
      $this->user = $user;
      $this->password = $password;
    }

    public function ToString() {
      return "--proxy '$user:$password@$ip:$port'";
    }
  }

}
