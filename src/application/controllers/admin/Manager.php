<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

}
