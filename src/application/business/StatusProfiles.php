<?php

namespace business {

    class StatusProfiles extends Business {

        public function __construct() {
            parent::__construct();
            
            $this->CI->load->model('db_model');
            $result = $this->CI->db_model->GetReferenceProfileStatus();
            if ($result) {
                while ($var_info = $result->fetch_array()) {
                    $this->{$var_info["status"]} = $var_info["id"];
                }
            } else {
                die("Can't load system config vars...!!");
            };
        }

        static public function Defines($const) {
            $cls = new ReflectionClass(__CLASS__);
            foreach ($cls->getConstants() as $key => $value) {
                if ($value == $const) {
                    return true;
                }
            }
            return false;
        }

    }

}