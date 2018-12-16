<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Worker
{
    require_once '../APIInstaWeb';
    require_once '../APIInstaWeb/Exception';
    require_once '../APIInstaWeb/Response';
    /**
     * Description of Robot
     *
     * @author dumbu
     */
    class Robot {

        public function __construct() {}

        public function do_follow_unfollow_work($Followeds_to_unfollow, DailyWork $daily_work, ErrorType &$error = NULL) 
        {}

        public function do_follow_work (DailyWork $daily_work, ErrorType &$error = NULL)
        {}

        public function do_unfollow_work ($Followeds_to_unfollow, DailyWork $daily_work, ErrorType &$error = NULL)
        {}

        public function  process_error($json_response)
        {}

        public function get_profiles_to_follow(DayliWork $daily_work, ErrorType &$error = NULL, &$page_info)
        {}

        public function process_get_insta_ref_prof_data_for_daily_report($content, \BussinesRefProfile $ref_prof)
        {}

        public function set_client_cookies_by_curl(int $client_id, string $curl, int $robot_id = NULL)
        {}

        public function temporal_log($data) 
        {}

        public function process_get_followers_error(DailyWork $daily_work, \bussines\cls\Client $client, int $quantity, Proxy $proxy)
        {}
    }
}