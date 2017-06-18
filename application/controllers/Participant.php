<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Participant extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('participants');
	}

	public function update_participant_time() {
		$participant_id = $this->input->post('participant_id');
		$diff_time = $this->input->post('diff_time');
		$this->participants->set_participant_time($participant_id, $diff_time);

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}
	
	public function get_participant_order() {
		$participants = $this->participants->get_participants();
		$json_return['participants'] = $participants;
		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	public function set_participant_state() {
		$participant_id = $this->input->post('participant_id');
		$is_active = $this->input->post('is_active');

		$this->participants->set_participant_state($participant_id, $is_active);

		$json_return['success'] = TRUE;
		echo json_encode($json_return);	
	}
}
