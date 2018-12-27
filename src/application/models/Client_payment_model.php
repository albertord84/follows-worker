<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: client_payment_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Client_payment_model extends CI_Model {

  function construct() {
    parent::construct();
  }

  function save($gateway_client_id, $dumbu_plane_id, $payment_key, $gateway_id) {
    $this->gateway_client_id = $gateway_client_id;
    $this->dumbu_plane_id = $dumbu_plane_id;
    $this->payment_key = $payment_key;
    $this->gateway_id = $gateway_id;
    $this->db->insert('client_payment', $this);

    return $this->db->insert_id();
  }

  function remove($dumbu_client_id) {
    $this->db->delete('client_payment', array('dumbu_client_id' => $dumbu_client_id));
  }

  function update($dumbu_client_id, $gateway_client_id, $dumbu_plane_id, $payment_key, $gateway_id) {
    $this->gateway_client_id = $gateway_client_id;
    $this->dumbu_plane_id = $dumbu_plane_id;
    $this->payment_key = $payment_key;
    $this->gateway_id = $gateway_id;

    $this->db->update('client_payment', $this, array('dumbu_client_id' => $dumbu_client_id));
  }

  function get_by_id($dumbu_client_id) {
    $this->db->where('dumbu_client_id', $dumbu_client_id);
    $query = $this->db->get('client_payment');

    return $query->row();
  }

function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('client_payment');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

