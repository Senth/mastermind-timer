<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Participants extends CI_Model {
	private static $TABLE = 'participant';
	private static $C_NAME = 'name';
	private static $C_ORDER = '`order`';
	private static $C_ID = 'id';
	private static $C_TIME = 'time';
	private static $C_ACTIVE = 'active';
	private static $DEFAULT_TIME = 720;

	public function get_participant_count() {
		$this->db->select('COUNT(*) AS count');
		$this->db->from(self::$TABLE);
		return $this->db->get()->row()->count;
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
		$this->db->update(self::$TABLE);
	}

	public function set_participant_state($participant_id, $is_active) {
		$is_active_value = $is_active === 'true' ? 1 : 0;
		log_message('debug', 'is_active: ' . $is_active . ', is_active_value: ' . $is_active_value);
		$this->db->set(self::$C_ACTIVE, $is_active_value);
		$this->db->where(self::$C_ID, $participant_id);
		$this->db->update(self::$TABLE);
	}

	public function get_participants() {
		$this->db->from(self::$TABLE);
		$this->db->order_by(self::$C_ORDER, 'ASC');
		return $this->db->get()->result();
	}
	
	public function reset() {
		$this->db->set(self::$C_TIME, self::$DEFAULT_TIME);
		$this->db->update(self::$TABLE);
	}
}
