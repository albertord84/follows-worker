<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

/**
 * @category CodeIgniter-Model: clients_Model
 * 
 * @access public
 *
 * @todo <description>
 * 
 */
class Clients_model extends CI_Model {

  function construct() {
    parent::construct();
  }

  function save($plane_id, $credit_card_number, $credit_card_status_id, $credit_card_cvc, $credit_card_name, $credit_card_exp_month, $credit_card_exp_year, $pay_day, $initial_order_key, $order_key, $pending_order_key, $actual_payment_value, $insta_id, $insta_followers_ini, $insta_following, $http_server_vars, $foults, $last_access, $cookies, $utm_source, $unfollow, $observation, $unfollow_total, $like_first, $paused, $ticket_peixe_urbano, $ticket_peixe_urbano_status_id, $purchase_counter, $retry_payment_counter, $purchase_access_token, $ticket_access_token, $retry_registration_counter, $proxy, $mundi_to_vindi) {
    $this->plane_id = $plane_id;
    $this->credit_card_number = $credit_card_number;
    $this->credit_card_status_id = $credit_card_status_id;
    $this->credit_card_cvc = $credit_card_cvc;
    $this->credit_card_name = $credit_card_name;
    $this->credit_card_exp_month = $credit_card_exp_month;
    $this->credit_card_exp_year = $credit_card_exp_year;
    $this->pay_day = $pay_day;
    $this->initial_order_key = $initial_order_key;
    $this->order_key = $order_key;
    $this->pending_order_key = $pending_order_key;
    $this->actual_payment_value = $actual_payment_value;
    $this->insta_id = $insta_id;
    $this->insta_followers_ini = $insta_followers_ini;
    $this->insta_following = $insta_following;
    $this->http_server_vars = $http_server_vars;
    $this->foults = $foults;
    $this->last_access = $last_access;
    $this->cookies = $cookies;
    $this->utm_source = $utm_source;
    $this->unfollow = $unfollow;
    $this->observation = $observation;
    $this->unfollow_total = $unfollow_total;
    $this->like_first = $like_first;
    $this->paused = $paused;
    $this->ticket_peixe_urbano = $ticket_peixe_urbano;
    $this->ticket_peixe_urbano_status_id = $ticket_peixe_urbano_status_id;
    $this->purchase_counter = $purchase_counter;
    $this->retry_payment_counter = $retry_payment_counter;
    $this->purchase_access_token = $purchase_access_token;
    $this->ticket_access_token = $ticket_access_token;
    $this->retry_registration_counter = $retry_registration_counter;
    $this->proxy = $proxy;
    $this->mundi_to_vindi = $mundi_to_vindi;
    $this->db->insert('clients', $this);

    return $this->db->insert_id();
  }

  function remove($user_id) {
    $this->db->delete('clients', array('user_id' => $user_id));
  }

  function update($user_id, $plane_id, $credit_card_number, $credit_card_status_id, $credit_card_cvc, $credit_card_name, $credit_card_exp_month, $credit_card_exp_year, $pay_day, $initial_order_key, $order_key, $pending_order_key, $actual_payment_value, $insta_id, $insta_followers_ini, $insta_following, $http_server_vars, $foults, $last_access, $cookies, $utm_source, $unfollow, $observation, $unfollow_total, $like_first, $paused, $ticket_peixe_urbano, $ticket_peixe_urbano_status_id, $purchase_counter, $retry_payment_counter, $purchase_access_token, $ticket_access_token, $retry_registration_counter, $proxy, $mundi_to_vindi) {
    $this->plane_id = $plane_id;
    $this->credit_card_number = $credit_card_number;
    $this->credit_card_status_id = $credit_card_status_id;
    $this->credit_card_cvc = $credit_card_cvc;
    $this->credit_card_name = $credit_card_name;
    $this->credit_card_exp_month = $credit_card_exp_month;
    $this->credit_card_exp_year = $credit_card_exp_year;
    $this->pay_day = $pay_day;
    $this->initial_order_key = $initial_order_key;
    $this->order_key = $order_key;
    $this->pending_order_key = $pending_order_key;
    $this->actual_payment_value = $actual_payment_value;
    $this->insta_id = $insta_id;
    $this->insta_followers_ini = $insta_followers_ini;
    $this->insta_following = $insta_following;
    $this->http_server_vars = $http_server_vars;
    $this->foults = $foults;
    $this->last_access = $last_access;
    $this->cookies = $cookies;
    $this->utm_source = $utm_source;
    $this->unfollow = $unfollow;
    $this->observation = $observation;
    $this->unfollow_total = $unfollow_total;
    $this->like_first = $like_first;
    $this->paused = $paused;
    $this->ticket_peixe_urbano = $ticket_peixe_urbano;
    $this->ticket_peixe_urbano_status_id = $ticket_peixe_urbano_status_id;
    $this->purchase_counter = $purchase_counter;
    $this->retry_payment_counter = $retry_payment_counter;
    $this->purchase_access_token = $purchase_access_token;
    $this->ticket_access_token = $ticket_access_token;
    $this->retry_registration_counter = $retry_registration_counter;
    $this->proxy = $proxy;
    $this->mundi_to_vindi = $mundi_to_vindi;

    $this->db->update('clients', $this, array('user_id' => $user_id));
  }

  function get_by_id($user_id) {
    $this->db->where('user_id', $user_id);
    $query = $this->db->get('clients');

    return $query->row();
  }

  function get_all($offset = 0, $rows = 0) {
    $this->db->limit($offset, $rows);
    $this->db->select('*')->from('clients');
    //$this->db->order_by('<field>', '<type>'); ==> asc/desc
    $query = $this->db->get();

    return $query->result();
  }

}
?>

