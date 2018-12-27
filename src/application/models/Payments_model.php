<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: payments_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Payments_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($client_id, $payment_form_id, $payment_cause_id, $payment_value, $captured_order_key, $plane, $date) {
    $this->client_id = $client_id;
    $this->payment_form_id = $payment_form_id;
    $this->payment_cause_id = $payment_cause_id;
    $this->payment_value = $payment_value;
    $this->captured_order_key = $captured_order_key;
    $this->plane = $plane;
    $this->date = $date;
    $this->db->insert('payments', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('payments', array('id' => $id));
  }

  function update($id, $client_id, $payment_form_id, $payment_cause_id, $payment_value, $captured_order_key, $plane, $date) {
    $this->client_id = $client_id;
    $this->payment_form_id = $payment_form_id;
    $this->payment_cause_id = $payment_cause_id;
    $this->payment_value = $payment_value;
    $this->captured_order_key = $captured_order_key;
    $this->plane = $plane;
    $this->date = $date;

    $this->db->update('payments', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('payments');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0) {
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('payments');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

