<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: payment_cause_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Payment_cause_model extends CI_Model {

  function construct() {
    parent::construct();
  }

  function save($cause) {
    $this->cause = $cause;
    $this->db->insert('payment_cause', $this);
    
    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('payment_cause', array('id' => $id));
  }

  function update($id, $cause) {
    $this->cause = $cause;
    $this->db->update('payment_cause', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('payment_cause');
    
    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('payment_cause');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

