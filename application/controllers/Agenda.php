<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('agendaitems');
		$this->load->model('agendas');
	}

	public function index() {
		$this->load->view('agenda_timer');
	}

	public function get_agenda() {
		$json_return['success'] = FALSE;

		// How many seconds have elapsed?
		$start_time = $this->agenda->get_start_time();
		if ($start_time > 0) {
			$diff_time = time() - $start_time;
			$json_return['seconds_elapsed'] = $diff_time;
		} else {
			$json_return['seconds_elapsed'] = 0;
		}

		// Get all agenda items
		$json_return['items'] = $this->model->agendaitems->get_items();

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
