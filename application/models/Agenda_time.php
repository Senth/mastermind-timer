<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_time extends CI_Model {
	private static $TABLE = 'agenda_time';
	private static $T_PARTICIPANT_TIME = 'participant_time';
	private static $C_AGENDA_ITEM_ID = 'agenda_item_id';
	private static $C_PARTICIPANT_ID = 'participant_id';
	private static $C_PARTICIPANT_ORDER = '`order`';
	private static $C_START_TIME = 'start_time';
	private static $C_END_TIME = 'end_time';

	public function get_times() {
		$this->db->from(self::$TABLE);
		return $this->db->get()->result();
	}

	public function get_active_item() {
		$this->db->from(self::$TABLE);
// 		$this->db->join(self::$T_PARTICIPANT_TIME, self::$TABLE . '.' . self::$C_PARTICIPANT_ID . ' = ' . self::$T_PARTICIPANT_TIME . '.' . self::$C_PARTICIPANT_ID);
		$this->db->order_by(self::$C_AGENDA_ITEM_ID, 'DESC');
		$this->db->order_by(self::$C_PARTICIPANT_ID, 'DESC');
		$this->db->limit(1);
		return $this->db->get()->row();
	}

	public function next_item($agenda_item_id, $participant_id, $time) {
		log_message('debug', 'next_item_id: ' . $agenda_item_id . ', participant_id: ' . $participant_id . ', time: ' . $time);
		$active_item = $this->get_active_item();

		// Set end time for last item
		if ($active_item !== NULL) {
			log_message('debug', 'End time for active item');
			$this->db->set(self::$C_END_TIME, $time);
			$this->db->where(self::$C_AGENDA_ITEM_ID, $active_item->agenda_item_id);

			if ($active_item->participant_id !== NULL) {
				$this->db->where(self::$C_PARTICIPANT_ID, $active_item->participant_id);
			}

			$this->db->update(self::$TABLE);
		}

		// Create new start time
		$this->db->set(self::$C_START_TIME, $time);
		$this->db->set(self::$C_AGENDA_ITEM_ID, $agenda_item_id);

		if ($participant_id !== NULL) {
			log_message('debug', 'Set participant id');
			$this->db->set(self::$C_PARTICIPANT_ID, $participant_id);
		}

		$this->db->insert(self::$TABLE);
	}

	public function reset() {
		$this->db->truncate(self::$TABLE);
	}
}
