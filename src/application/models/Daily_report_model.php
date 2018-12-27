<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: daily_report_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Daily_report_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($client_id, $followings, $followers, $date) {
    $this->client_id = $client_id;
    $this->followings = $followings;
    $this->followers = $followers;
    $this->date = $date;
    $this->db->insert('daily_report', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('daily_report', array('id' => $id));
  }

  function update($id, $client_id, $followings, $followers, $date) {
    $this->client_id = $client_id;
    $this->followings = $followings;
    $this->followers = $followers;
    $this->date = $date;

    $this->db->update('daily_report', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('daily_report');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0) {
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('daily_report');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

