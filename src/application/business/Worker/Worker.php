<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace business\cls\worker
{
    /**
     * Description of Worker
     *
     * @author dumbu
     */
    class Worker {
        //put your code here
        /*
         * +id
+IP
+robots
+config
+work_queue = array()
+dir
+Robot
+Gmail
         * ------------------------------------------
+__construct($DB = NULL, $id = -1)

>>>>>> +get_worker_config() <<<<<<<<                      //NUEVA NO EXISTE
>>>>>> +request_follow_unfollow_work() <<<<<<<            //NUEVA NO EXISTE
>>>>>> +send_check_insta_user_work($Client) <<<<<<        //NUEVA NO EXISTE
>>>>>> +have_work() <<<<<<<<                              //NUEVA NO EXISTE
>>>>>> +get_work() : DailyWork <<<<<<<                    //NUEVA NO EXISTE


-prepare_daily_work($client_id =  NULL, $not_mail = false) : VOID

+do_client_work($daily_work) :BOOL    //ANTIGUA -> do_follow_unfollow_work

+save_follow_unfollow_work($Followeds_to_unfollow, $Ref_profile_follows, $daily_work) : BOOL

+get_work_by_id($reference_id) : DailyWork

+do_work($client_id = NULL, $n= NULL, $rp = NULL) :  VOID

+insert_daily_work($Ref_Prof, $to_follow, $to_unfollow, $login_data): VOID

+elete_daily_work($ref_prof_id) : VOID

+truncate_daily_work() : VOID
*/
        
        public $id;
        private $config;
        public $work_queue = array();
        public $dir;
        public $robot;
        public $mail;
        
        public function __construct($id = -1) {
            $this->id = $id;
        }
        
        public function get_worker_config(){ return $config; }
        
        public function prepare_daily_work(bool $not_mail = false)
        {}
        
        public function prepare_client_daily_work(int $client_id, bool $not_mail = false)
        {}
        
        public function request_current_work(\business\cls\Client $client = NULL)
        {}
        
        public function do_work(int $client_id = NULL, int $n= NULL, int $rp = NULL)
        {}
        
        //do follow unfollow work
        private function do_client_work(DailyWork $daily_work)
        {}
        
        public function get_work(){}
        
        public function get_work_by_id(int $id){}
        
        
        private function insert_daily_work(\BusinessRefProfile $ref_prof, $to_follow, $to_unfollow, $cookies ){}
        
        private function delete_daily_work(int $ref_prof_id) {}
        
        public function truncate_daily_work(){}
    }
}
