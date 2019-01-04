<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: pendences_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Pendences_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($client_id, $text, $init_date, $event_date, $resolved_date, $number, $frequency, $closed_message) {
    $this->client_id = $client_id;
    $this->text = $text;
    $this->init_date = $init_date;
    $this->event_date = $event_date;
    $this->resolved_date = $resolved_date;
    $this->number = $number;
    $this->frequency = $frequency;
    $this->closed_message = $closed_message;
    $this->db->insert('pendences', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('pendences', array('id' => $id));
  }

  function update($id, $client_id, $text, $init_date, $event_date, $resolved_date, $number, $frequency, $closed_message) {
    $this->client_id = $client_id;
    $this->text = $text;
    $this->init_date = $init_date;
    $this->event_date = $event_date;
    $this->resolved_date = $resolved_date;
    $this->number = $number;
    $this->frequency = $frequency;
    $this->closed_message = $closed_message;

    $this->db->update('pendences', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('pendences');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('pendences');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

