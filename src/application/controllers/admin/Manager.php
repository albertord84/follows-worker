<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Manager extends CI_Controller {

  public function __construct() {
    parent::__construct();
  }

  public function index() {
    echo "Controller: <b>" . __CLASS__ . "</b> cargado.";
  }

  public function curl() {
    echo "Aqui se hara la gestion de las curl";
  }
  
  public function protocol() {
    echo "Aqui se hara la gestion de las curl";
  }
}
