<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: payment_gateway_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Payment_gateway_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($name, $description, $url) {
    $this->name = $name;
    $this->description = $description;
    $this->url = $url;
    $this->db->insert('payment_gateway', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('payment_gateway', array('id' => $id));
  }

  function update($id, $name, $description, $url) {
    $this->name = $name;
    $this->description = $description;
    $this->url = $url;

    $this->db->update('payment_gateway', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('payment_gateway');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('payment_gateway');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

