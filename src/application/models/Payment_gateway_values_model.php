<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: payment_gateway_values_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Payment_gateway_values_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($value, $description, $payment_gateway_id) {
    $this->value = $value;
    $this->description = $description;
    $this->payment_gateway_id = $payment_gateway_id;
    $this->db->insert('payment_gateway_values', $this);

    return $this->db->insert_id();
  }

  function remove($key) {
    $this->db->delete('payment_gateway_values', array('key' => $key));
  }

  function update($key, $value, $description, $payment_gateway_id) {
    $this->value = $value;
    $this->description = $description;
    $this->payment_gateway_id = $payment_gateway_id;

    $this->db->update('payment_gateway_values', $this, array('key' => $key));
  }

  function get_by_id($key) {
    $this->db->where('key', $key);
    $query = $this->db->get('payment_gateway_values');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('payment_gateway_values');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

