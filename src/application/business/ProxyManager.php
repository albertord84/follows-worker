<?php

namespace business {

  /**
   * Description of ProxyManager
   *
   * @author jose
   */
  class ProxyManager extends Business {

    function __construct() {
      parent::__construct();
      $this->CI->load->model('db_model');
      //$this->CI->load_model("client_model"); NO SE USA AQUI
    }

    public function UpdateUserProxy() 
    {
      /*$clients = $this->CI->db_model->GetClientWithouProxy();
      $proxies = $this->CI->db_model->GetNotResrevedProxyList();
      $proxiesLst = array();
      while (($proxy = $proxies->fetch_object())) {
        $proxy->cnt = $this->db_model->GetProxyClientCounts($proxy->proxy)->fetch_object()->cnt;
        array_push($proxiesLst, $proxy);
      }
      while (($client = $clients->fetch_object())) {
        $min_proxy = $proxiesLst[0];
        foreach ($proxiesLst as $p) {
          if ($p->cnt < $min_proxy->cnt) {
            $min_proxy = $p;
          }
          if ($min_proxy->cnt == "0" || $min_proxy->cnt === 0) {
            break;
          }
        }
        $res = $this->CI->db_model->SetProxyToClient($client->user_id, $min_proxy->idProxy);
        $min_proxy->cnt++;
      }*/
    }

    public function GetNextProxy() {
      
    }

    public function GetReservedProxy() {
      
    }

  }

}