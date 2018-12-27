<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: washdog_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Washdog_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($user_id, $action, $date) {
    $this->user_id = $user_id;
    $this->action = $action;
    $this->date = $date;
    $this->db->insert('washdog', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('washdog', array('id' => $id));
  }

  function update($id, $user_id, $action, $date) {
    $this->user_id = $user_id;
    $this->action = $action;
    $this->date = $date;

    $this->db->update('washdog', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('washdog');

    return $query->row();
  }

  function get_all() {
    $this->db->select('*')->from('washdog');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

