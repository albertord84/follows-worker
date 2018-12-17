<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: Proxy_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Proxy_model extends CI_Model {

  function construct() {
    parent::construct();
  }

  function save($proxy, $proxy_user, $proxy_password, $port, $isreserved) {
    $this->proxy = $proxy;
    $this->proxy_user = $proxy_user;
    $this->proxy_password = $proxy_password;
    $this->port = $port;
    $this->isreserved = $isreserved;
    $this->db->insert('Proxy', $this);

    return $this->db->insert_id();
  }

  function remove($idproxy) {
    $this->db->delete('Proxy', array('idproxy' => $idproxy));
  }

  function update($idproxy, $proxy, $proxy_user, $proxy_password, $port, $isreserved) {
    $this->proxy = $proxy;
    $this->proxy_user = $proxy_user;
    $this->proxy_password = $proxy_password;
    $this->port = $port;
    $this->isreserved = $isreserved;

    $this->db->update('Proxy', $this, array('idproxy' => $idproxy));
  }

  function get_by_id($idproxy) {
    $this->db->where('idproxy', $idproxy);
    $query = $this->db->get('Proxy');

    return $query->row();
  }

function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('Proxy');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

