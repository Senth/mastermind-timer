<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_items extends CI_Model {
	private static $T_AGENDA_ITEMS = '`agenda_items`';
	private static $C_ID = 'id';
	private static $C_DESCRIPTION = '`description`';
	private static $C_IS_TIME_EDITABLE = '`is_time_editable`';
	private static $C_TIME = 'time';
	private static $C_IS_ALL_PARTICIPANTS = '`is_all_participants`';

	public function __construct() {
		parent::__construct();
	}

	public function get_items() {
		$this->db->from(self::$T_AGENDA_ITEMS);
		$this->db->order_by(self::$C_ID, 'ASC');

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

	public function exists($agenda_id) {
		$this->db->from(self::$T_AGENDA_ITEMS);
		$this->db->where(self::$C_ID, $agenda_id);
		return $this->db->count_all_results() == 1;
	}
}
