<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('agenda_items');
		$this->load->model('agendas');
	}

	public function index() {
		$header['title'] = 'Mastermind Timer';
		$header['js_asset'] = 'agenda_timer.js';
		$this->load->view('header', $header);

		$this->load->view('agenda_timer');

		$this->load->view('footer');
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
		log_message('debug', 'Elapsed seconds: ' + $json_return['seconds_elapsed']);

		// Get all agenda items
		$json_return['items'] = $this->agenda_items->get_items();

		// Get participant times
		$json_return['participant_times'] = $this->agenda_items->get_participant_time();

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	public function update_participant_time() {
		$participant_id = $this->input->post('participant_id');
		$diff_time = $this->input->post('diff_time');
		$this->agenda_items->set_participant_time($participant_id, $diff_time);

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	public function reset() {
		$this->agenda_items->reset();
	}

	public function start() {
		$this->reset();

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
		$this->agendas->set_start_time(time());
	}
}
