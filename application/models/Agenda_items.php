<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_items extends CI_Model {
	private static $T_AGENDA_ITEMS = '`agenda_items`';
	private static $C_ID = 'id';
	private static $C_DESCRIPTION = '`description`';
	private static $C_ORDER = '`order`';
	private static $C_IS_TIME_EDITABLE = '`is_time_editable`';
	private static $C_TIME = 'time';
	private static $C_IS_ALL_PARTICIPANTS = '`is_all_participants`';

	private static $T_PARTICIPANT_TIME = '`participant_time`';
	private static $C_NAME = '`name`';
	
	private static $DEFAULT_TIME = 540;

	public function __construct() {
		parent::__construct();
	}

	public function get_items() {
		$this->db->from(self::$T_AGENDA_ITEMS);
		$this->db->order_by(self::$C_ORDER, 'ASC');

		return $this->db->get()->result();
	}

	public function is_all_participants($agenda_id) {
		$this->db->select(self::$C_IS_ALL_PARTICIPANTS);
		$this->db->from(self::$T_AGENDA_ITEMS);
		$this->db->where(self::$C_ID, $agenda_id);
		$this->db->limit(1);
		$row = $this->db->get()->row();
		if ($row !== NULL) {
			log_message('debug', 'is_all_participants(): ' . $row->is_all_participants);
			return $row->is_all_participants == 1;
		} else {
			return false;
		}
	}

	public function set_participant_time($participant_id, $time_diff) {
		$value = self::$C_TIME;
		if ($time_diff > 0) {
			$value .= '+';
		}
		$value .= $time_diff;
		log_message('debug', 'Participant id: ' . $participant_id . ', Diff time: ' . $value);
		$this->db->set(self::$C_TIME, self::$C_TIME . '+' . $time_diff, FALSE);
		$this->db->where(self::$C_ID, $participant_id); 
		$this->db->update(self::$T_PARTICIPANT_TIME);
	}

	public function get_participant_time() {
		$this->db->from(self::$T_PARTICIPANT_TIME);
		return $this->db->get()->result();
	}

	public function get_participant_count() {
		$this->db->select('COUNT(*) AS count');
		$this->db->from(self::$T_PARTICIPANT_TIME);
		return $this->db->get()->row()->count;
	}

	public function reset() {
		$this->db->set(self::$C_TIME, self::$DEFAULT_TIME);
		$this->db->update(self::$T_PARTICIPANT_TIME);
	}
}
