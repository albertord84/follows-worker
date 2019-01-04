<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: ticket_bank_Model
 * 
 * @access public
 * 
 * @todo <description>
 * 
 */
class Ticket_bank_model extends CI_Model {
  function construct() {
    parent::construct();
  }

  function save($client_id, $name_in_ticket, $cpf, $ticket_link, $amount_months, $value, $document_number, $ticket_order_key, $generated_date, $payed_date, $ticket_bank_option, $cep, $street_address, $house_number, $neighborhood_address, $municipality_address, $state_address) {
    $this->client_id = $client_id;
    $this->name_in_ticket = $name_in_ticket;
    $this->cpf = $cpf;
    $this->ticket_link = $ticket_link;
    $this->amount_months = $amount_months;
    $this->value = $value;
    $this->document_number = $document_number;
    $this->ticket_order_key = $ticket_order_key;
    $this->generated_date = $generated_date;
    $this->payed_date = $payed_date;
    $this->ticket_bank_option = $ticket_bank_option;
    $this->cep = $cep;
    $this->street_address = $street_address;
    $this->house_number = $house_number;
    $this->neighborhood_address = $neighborhood_address;
    $this->municipality_address = $municipality_address;
    $this->state_address = $state_address;
    $this->db->insert('ticket_bank', $this);

    return $this->db->insert_id();
  }

  function remove($id) {
    $this->db->delete('ticket_bank', array('id' => $id));
  }

  function update($id, $client_id, $name_in_ticket, $cpf, $ticket_link, $amount_months, $value, $document_number, $ticket_order_key, $generated_date, $payed_date, $ticket_bank_option, $cep, $street_address, $house_number, $neighborhood_address, $municipality_address, $state_address) {
    $this->client_id = $client_id;
    $this->name_in_ticket = $name_in_ticket;
    $this->cpf = $cpf;
    $this->ticket_link = $ticket_link;
    $this->amount_months = $amount_months;
    $this->value = $value;
    $this->document_number = $document_number;
    $this->ticket_order_key = $ticket_order_key;
    $this->generated_date = $generated_date;
    $this->payed_date = $payed_date;
    $this->ticket_bank_option = $ticket_bank_option;
    $this->cep = $cep;
    $this->street_address = $street_address;
    $this->house_number = $house_number;
    $this->neighborhood_address = $neighborhood_address;
    $this->municipality_address = $municipality_address;
    $this->state_address = $state_address;

    $this->db->update('ticket_bank', $this, array('id' => $id));
  }

  function get_by_id($id) {
    $this->db->where('id', $id);
    $query = $this->db->get('ticket_bank');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0){	
    $this->db->limit($offset, $rows);	
    $this->db->select('*')->from('ticket_bank');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }
}

?>

