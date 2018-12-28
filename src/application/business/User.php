<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace business\cls{
    require_once 'user_role.php';
    require_once 'user_status.php';

/**
 * Description of User
 *
 * @author jose
 */
class User  {
      /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * Variable defined as setter and getter reference example,
         * study carefully:
         * If function with same variable name is defined, the magic getter 
         * and setter will called without (resp. with) the $value param, 
         * so it function can determine if should do a get or o set..
         * 
         * @access public
         */
        public $id;       
        public function id($value = NULL) {
            if (isset($value)) {
                $this->id = $value;
            }
            else {
                return $this->id;
            }
        }

        /**
         * 
         * @access public
         */
        public $name;

        /**
         * 
         * @access public
         */
        public $login;

        /**
         * 
         * @access public
         */
        public $pass;

        /**
         * 
         * @access public
         */
        public $email;

        /**
         * 
         * @access public
         */
        public $telf;

        /**
         * 
         * @access public
         */
        public $role_id;

        /**
         * 
         * @access public
         */
        public $status_id;

        /**
         * 
         * @access public
         */
        public $languaje;

        /**
         * 
         */
        function __construct() {
            //$this->load->model('User_model');
        }

        /**
         * 
         *
         * @return unsigned short
         * @access public
         */
        public function do_login($user_name,$user_pass) 
         { 
            echo $user_name;
         }

// end of member function do_login

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function update_user() {
            
        }

// end of member function update_user

        /**
         * 
         *
         * @param serial user_id 

         * @return User
         * @access public
         */
        public function load_user($user_id = 0) {
            
        }

// end of member function load_user

        /**
         * 
         *
         * @return bool
         * @access public
         */
        public function disable_account() {
            
        }        
    }
}
