<?php if (!defined('BASEPATH'))   exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: payment_form_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Payment_form_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($payment_form) {
    $this->payment_form = $payment_form;
    $this->db->insert('payment_form', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('payment_form', array('id' => $id));
  }

  function update($id, $payment_form) {
    $this->payment_form = $payment_form;
    $this->db->update('payment_form', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('payment_form');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('payment_form');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

