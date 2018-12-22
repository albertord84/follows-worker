<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ApiInstaWeb
{


    /**
     * Description of Proxy
     *
     * @author jose
     */
    class Proxy {
        //put your code here
        public  $ip;
        public  $port;
        public  $user;
        public  $password;
        
        public function __construct(string $ip, string $port, string $user, string $password ) {
            $this->ip = $ip;
            $this->port = $port;
            $this->user = $user;
            $this->password = $password;
        }

        public function  ToString()
        {
            return "--proxy '$user:$password@$ip:$port'";
        }
    }
}
