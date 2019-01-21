<?php

namespace business {

  require_once config_item('business-user-class');

  /**
   * @category Business class
   * 
   * @access public
   *
   * @todo Define an ProxyManager business class.
   * 
   */
  class ProxyManager extends Business {

    function __construct() {
      $ci = &get_instance();
      $ci->load->model('db_model');
    }

    public function UpdateUserProxy() {
      /*$ci = &get_instance();
        $clients = $ci->db_model->GetClientWithouProxy();
        $proxies = $ci->db_model->GetNotResrevedProxyList();
        $proxiesLst = array();
        while (($proxy = $proxies->fetch_object())) {
        $proxy->cnt = $ci->db_model->GetProxyClientCounts($proxy->proxy)->fetch_object()->cnt;
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
        $res = $ci->db_model->SetProxyToClient($client->user_id, $min_proxy->idProxy);
        $min_proxy->cnt++;
        } */
    }

    public function GetNextProxy() {
      
    }

    public function GetReservedProxy() {
      
    }

  }

}