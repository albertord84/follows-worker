<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: users_Model
 *
 * @access public
 * 
 * @todo <description>
 * 
 */
class Users_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($role_id, $name, $login, $pass, $email, $phone_ddi, $phone_ddd, $phone_number, $status_id, $status_date, $language, $init_date, $end_date) {
    $this->role_id = $role_id;
    $this->name = $name;
    $this->login = $login;
    $this->pass = $pass;
    $this->email = $email;
    $this->phone_ddi = $phone_ddi;
    $this->phone_ddd = $phone_ddd;
    $this->phone_number = $phone_number;
    $this->status_id = $status_id;
    $this->status_date = $status_date;
    $this->language = $language;
    $this->init_date = $init_date;
    $this->end_date = $end_date;
    $this->db->insert('users', $this);
    
    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('users', array('id' => $id));
  }

  function update($id, $role_id, $name, $login, $pass, $email, $phone_ddi, $phone_ddd, $phone_number, $status_id, $status_date, $language, $init_date, $end_date) {
    $this->role_id = $role_id;
    $this->name = $name;
    $this->login = $login;
    $this->pass = $pass;
    $this->email = $email;
    $this->phone_ddi = $phone_ddi;
    $this->phone_ddd = $phone_ddd;
    $this->phone_number = $phone_number;
    $this->status_id = $status_id;
    $this->status_date = $status_date;
    $this->language = $language;
    $this->init_date = $init_date;
    $this->end_date = $end_date;

    $this->db->update('users', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('users');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('users');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

