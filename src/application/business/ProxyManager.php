<?php

namespace business\cls {
    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */
    require_once 'DB.php';

    /**
     * Description of ProxyManager
     *
     * @author jose
     */
    class ProxyManager extends Business {
        
        function __construct() {
            $this->load->model('db_model');
        }
        
        public function UpdateUserProxy()
        {
            $this->CI->load_model("client_model");
            //<DB FUNC>
            $DB = new \business\DB\DB();
            $clients = $this->db_model->GetClientWithouProxy();
            $proxies = $this->db_model->GetNotResrevedProxyList();
            $proxiesLst = array();
            while(($proxy = $proxies->fetch_object()))
            {
                $proxy->cnt = $this->db_model->GetProxyClientCounts($proxy->proxy)->fetch_object()->cnt;
                array_push($proxiesLst, $proxy);
            }
            while(($client = $clients->fetch_object()))
            {
                $min_proxy = $proxiesLst[0];
                foreach ($proxiesLst as $p)
                {
                    if($p->cnt < $min_proxy->cnt)
                    {
                        $min_proxy = $p;
                    }
                    if($min_proxy->cnt == "0" || $min_proxy->cnt === 0)
                    {
                        break;
                    }
                }
                $res = $this->db_model->SetProxyToClient($client->user_id, $min_proxy->idProxy);
                $min_proxy->cnt++;
            }
        }

        public function GetNextProxy()
        {}

        public  function GetReservedProxy()
        {}
    }
}