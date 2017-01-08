<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('install_db');
	}

	public function index() {
		$installed = $this->install_db->is_installed();
		$data['installed'] = $installed;
		$this->load->view('install', $data);
	}

	public function install() {
		$this->install_db->install();
		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}
}
