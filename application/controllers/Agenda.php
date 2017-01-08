<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('agenda_items');
		$this->load->model('agendas');
	}

	public function index() {
		$this->load->view('agenda_timer');
	}

	public function get_agenda() {
		// How many seconds have elapsed?
		$start_time = $this->agendas->get_start_time();
		if ($start_time > 0) {
			$diff_time = time() - $start_time;
			$json_return['seconds_elapsed'] = $diff_time;
		} else {
			$json_return['seconds_elapsed'] = 0;
		}

		// Get all agenda items
		$json_return['items'] = $this->agenda_items->get_items();
		$json_return['success'] = TRUE;

		echo json_encode($json_return);
	}

	public function reset() {
		$this->agenda->set_start_time(0);
		$this->agendaitems->reset();
	}

	public function start() {
		$this->agenda->set_start_time(time());
	}
}
