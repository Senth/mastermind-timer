<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agenda_items extends CI_Model {
	private static $TABLE = 'agenda_items';
	private static $C_ID = 'id';
	private static $C_DESCRIPTION = 'description';
	private static $C_ORDER = 'order';
	private static $C_TIME_DEFAULT = 'time_default';
	private static $C_IS_TIME_EDITABLE = 'is_time_editable';
	private static $C_IS_TIME_EXTRA = 'is_time_extra';
	private static $C_TIME = 'time';
	private static $C_IS_ALL_PARTICIPANTS = 'is_all_participants';

	public function __construct() {
		parent::__construct();
	}

	public function get_items() {
		$this->db->from(self::$TABLE);
		$this->db->order_by(self::$C_ORDER, 'ASC');

		return $this->db->get()->result_array();
	}

	public function reset() {
		$this->db->set(self::$C_TIME, self::$C_TIME_DEFAULT);
		$this->db->update(self::$TABLE);
	}
}
