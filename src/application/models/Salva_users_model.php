<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: salva_users_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Salva_users_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($name, $login, $pass, $email, $telf, $role_id, $status_id, $status_date, $languaje, $init_date, $end_date) {
    $this->name = $name;
    $this->login = $login;
    $this->pass = $pass;
    $this->email = $email;
    $this->telf = $telf;
    $this->role_id = $role_id;
    $this->status_id = $status_id;
    $this->status_date = $status_date;
    $this->languaje = $languaje;
    $this->init_date = $init_date;
    $this->end_date = $end_date;
    $this->db->insert('salva_users', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('salva_users', array('id' => $id));
  }

  function update($id, $name, $login, $pass, $email, $telf, $role_id, $status_id, $status_date, $languaje, $init_date, $end_date) {
    $this->name = $name;
    $this->login = $login;
    $this->pass = $pass;
    $this->email = $email;
    $this->telf = $telf;
    $this->role_id = $role_id;
    $this->status_id = $status_id;
    $this->status_date = $status_date;
    $this->languaje = $languaje;
    $this->init_date = $init_date;
    $this->end_date = $end_date;

    $this->db->update('salva_users', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('salva_users');
    
    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('salva_users');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

