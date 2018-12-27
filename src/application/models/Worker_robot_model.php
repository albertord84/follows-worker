<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: worker_robot_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Worker_robot_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($worker_id, $robot_id) {
    $this->worker_id = $worker_id;
    $this->robot_id = $robot_id;
    $this->db->insert('worker_robot', $this);

    return $this->db->insert_id();
  }

  /* function remove ($) {

    $this->db->delete('worker_robot', array('' => $));

    } */

  function update($worker_id, $robot_id) {
    $this->worker_id = $worker_id;
    $this->robot_id = $robot_id;

    //$this->db->update('worker_robot', $this, array('' => $));
  }

  /*   * function get_by_id ($) {

    $this->db->where('', $);

    $query = $this->db->get('worker_robot');



    return $query->row();

    } */

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('worker_robot');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

