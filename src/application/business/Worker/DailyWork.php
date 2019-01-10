<?php

namespace business\worker{

     require_once '../system_config.php';
     require_once '../Client.php';
     require_once '../Followed.php';

/**
 * Description of DailyWork
 *
 * @author dumbu
 */
class DailyWork extends \business\Business{
        /** Aggregations: */
        /** Compositions: */
        /*         * * Attributes: ** */

        /**
         * 
         * @access public
         */
        public $Client;

        /**
         * 
         * @access public
         */
        public $Ref_profile_follows = array();

        /**
         * 
         * @access public
         */
        public $Followeds_to_unfollow = array();

        /**
         * Elapsed time since last access to this $Client
         * @access public
         */
        public $last_accesss;

        /**
         * 
         * @access public
         */
        public $foults;

        function __construct() {
            $this->Client = new Client();
            $this->load->model('db_model');
        }

    
            public function is_work_done($config) {
            
        }

        public function get_unfollow_data($client_id) {
            // Get profiles to unfollow today for this Client...(i.e the last followed)
            //$DB = new \follows\cls\DB();
            $unfollow_data = $this->db_model->get_unfollow_data($client_id);
            while ($Followed = $unfollow_data->fetch_object()) {
                $To_Unfollow = new \follows\cls\Followed();
                // Update Ref Prof Data
                $To_Unfollow->id = $Followed->id;
                $To_Unfollow->followed_id = $Followed->followed_id;
                array_push($this->Followeds_to_unfollow, $To_Unfollow);
            }
        }
   }
}