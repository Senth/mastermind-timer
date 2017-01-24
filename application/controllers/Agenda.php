<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('agenda_items');
		$this->load->model('agenda_time');
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
		// Get all agenda items
		$items = $this->agenda_items->get_items();
		$participant_times = $this->agenda_items->get_participant_time();

		// Create agenda
		foreach ($items as $item) {
			if ($item->is_all_participants) {
				foreach ($participant_times as $participant) {
					$json_return['items'][] = $this->create_agenda_row($item, $participant);
				}
			} else {
				$json_return['items'][] = $this->create_agenda_row($item, NULL);
			}
		}

		// Get participant times
		foreach ($participant_times as $participant) {
			$json_return['participant_times'][$participant->id] = $participant;
		}

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	private function create_agenda_row($item, $participant) {
		$json_item = new stdClass();
		$json_item->id = $item->id;
		$json_item->description = $item->description;
		if ($participant !== NULL) {
			$json_item->participant_id = $participant->id;
			$json_item->is_time_editable = $item->is_time_editable;
		}
		return $json_item;
	}

	public function update_participant_time() {
		$participant_id = $this->input->post('participant_id');
		$diff_time = $this->input->post('diff_time');
		$this->agenda_items->set_participant_time($participant_id, $diff_time);

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	public function next_item() {
		$active_item = $this->agenda_time->get_active_item();
		$cParticipants = $this->agenda_items->get_participant_count();

		// Calculate next item
		if ($active_item !== NULL) {
			$agenda_item_id = $active_item->agenda_item_id;
			$participant_id = $active_item->participant_id;
			$go_to_next_agenda_item = false;

			// Next participant
			if ($participant_id !== NULL) {
				$participant_id++;

				// Gone through all participants. Go to next agenda item
				if ($participant_id > $cParticipants) {
					$go_to_next_agenda_item = true;
				}
			} else {
				$go_to_next_agenda_item = true;
			}

			if ($go_to_next_agenda_item) {
				$agenda_item_id++;
				if ($this->agenda_items->is_all_participants($agenda_item_id)) {
					$participant_id = 1;
				} else {
					$participant_id = NULL;
				}
			}
		} else {
			$agenda_item_id = 1;
			if ($this->agenda_items->is_all_participants($agenda_item_id)) {
				$participant_id = 1;
			} else {
				$participant_id = NULL;
			}
		}

		// Set next item
		log_message('debug', 'participant_id: ' . $participant_id);
		$this->agenda_time->next_item($agenda_item_id, $participant_id, $this->get_elapsed_time());
	}

	private function get_elapsed_time() {
		$start_time = $this->agendas->get_start_time();
		if ($start_time > 0) {
			$diff_time = time() - $start_time;
			return $diff_time;
		} else {
			return 0;
		}
	}

	public function get_times() {
		// How many seconds have elapsed?
		$json_return['elapsed_time'] = $this->get_elapsed_time();
		log_message('debug', 'Elapsed seconds: ' + $json_return['elapsed_time']);

		$items = $this->agenda_items->get_items();
		$participants = $this->agenda_items->get_participant_time();
		$item_times = $this->get_sorted_item_times();

		foreach ($items as $item) {
			if ($item->is_all_participants) {
				foreach ($participants as $participant) {
					$json_return['items'][] = $this->create_time_row($item, $participant, $item_times);
				}
			} else {
				$json_return['items'][] = $this->create_time_row($item, NULL, $item_times);
			}
		}

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
	}

	private function get_sorted_item_times() {
		$times = $this->agenda_time->get_times();

		if (is_array($times)) {
			$sorted_times = array();
			foreach ($times as $time) {
				if ($time->participant_id !== NULL) {
					$sorted_times[$time->agenda_item_id][$time->participant_id] = $time;
				} else {
					$sorted_times[$time->agenda_item_id] = $time;
				}
			}

			return $sorted_times;
		} else {
			return array();
		}
	}

	private function create_time_row($item, $participant, $item_times) {
		$json_item = new stdClass();
		$json_item->id = $item->id;
		if ($item->is_all_participants) {
			if ($item->is_time_editable) {
				$json_item->time = $participant->time;
				$json_item->time_default = $item->time;
			} else {
				$json_item->time = $item->time;
			}

			if (isset($item_times[$item->id]) && isset($item_times[$item->id][$participant->id])) {
				$item_time = $item_times[$item->id][$participant->id];
				$json_item->start_time = $item_time->start_time;
				$json_item->end_time = $item_time->end_time;
			}
		} else {
			$json_item->time = $item->time;

			if (isset($item_times[$item->id])) {
				$item_time = $item_times[$item->id];
				$json_item->start_time = $item_time->start_time;
				$json_item->end_time = $item_time->end_time;
			}
		}
		return $json_item;
	}

	public function reset() {
		$this->agenda_items->reset();
		$this->agenda_time->reset();
	}

	public function start() {
		$this->reset();

		$json_return['success'] = TRUE;
		echo json_encode($json_return);
		$this->agendas->set_start_time(time());
		$this->next_item();
	}
}
