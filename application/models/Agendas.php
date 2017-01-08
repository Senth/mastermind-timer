<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendas extends CI_Model {
	private static $TABLE = 'agenda';
	private static $C_ID = 'id';
	private static $C_START_TIME = 'start_time';

	public function __construct() {
		parent::__construct();
	}

	public function get_start_time() {
		$this->db->from(self::$TABLE);
		$this->db->select(self::$C_START_TIME);
		$this->db->order_by(self::$C_START_TIME, 'ASC');
		$this->db->limit(1);

		$row = $this->db->get()->row();
		if (isset($row)) {
			return $row()->start_time;
		} else {
			return 0;
		}
	}

	public function set_start_time($start_time) {
		$this->db->set(self::$C_START_TIME, $start_time);
		$this->db->update(self::$TABLE);
	}
}
