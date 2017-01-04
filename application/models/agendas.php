<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendas extends CI_Model {
	private static $TABLE = 'agenda';
	private static $C_ID = 'id';
	private static $C_START_TIME = 'start_time';

	public function __construct() {
		parent::__construct();
	}

	public function get_start_time() {
		$this->db->from($TABLE);
		$this->db->select($C_START_TIME);
		$this->db->limit(1);

		return $this->db->get()->row()->start_time;
	}

	public function set_start_time($start_time) {
		$this->db->set(self::$C_START_TIME, $start_time);
		$this->db->update($TABLE);
	}
}
