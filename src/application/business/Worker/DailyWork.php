<?php

namespace business\worker{
  
  use business\Client;
  use business\Business; 
  use business\SystemConfig;
  
  require_once config_item('business-class');
  require_once config_item('business-client-class');
  require_once config_item('business-system_config-class');


  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an DailyWork worker class.
   * 
   */
class DailyWork extends Business{
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
          $ci = &get_instance();
      
          $ci->load->model('db_model');
          //$ci->load->library("InstaApiWeb/InstaApi_lib", null, 'InstaApi_lib');
          
          $this->Client = new Client();
        }

    
        public function is_work_done($config) {
            
        }

        public function get_unfollow_data($client_id) {
            // Get profiles to unfollow today for this Client...(i.e the last followed)
          /*  $unfollow_data = $this->db_model->get_unfollow_data($client_id);
            while ($Followed = $unfollow_data->fetch_object()) {
                $To_Unfollow = new \follows\cls\Followed();
                // Update Ref Prof Data
                $To_Unfollow->id = $Followed->id;
                $To_Unfollow->followed_id = $Followed->followed_id;
                array_push($this->Followeds_to_unfollow, $To_Unfollow);
            }*/
        }
   }
}