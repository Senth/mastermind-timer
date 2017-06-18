<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agendas extends CI_Model {
	private static $TABLE = 'agenda';
	private static $C_ID = 'id';
	private static $C_START_TIME = 'start_time';
	private static $C_END_TIME = 'end_time';
	
	public function __construct() {
		parent::__construct();
	}

	public function get_start_time() {
		$this->db->from(self::$TABLE);
		$this->db->select(self::$C_START_TIME);
		$this->db->order_by(self::$C_START_TIME, 'DESC');
		$this->db->limit(1);

		$row = $this->db->get()->row();
		if (isset($row)) {
			return $row->start_time;
		} else {
			return 0;
		}
	}

	public function get_end_time() {
		$this->db->from(self::$TABLE);
		$this->db->select(self::$C_END_TIME);
		$this->db->order_by(self::$C_START_TIME, 'DESC');
		$this->db->limit(1);

		$row = $this->db->get()->row();
		if (isset($row)) {
			return $row->end_time;
		} else {
			return 0;
		}
		
	}

	public function set_start_time($start_time) {
		$this->db->set(self::$C_START_TIME, $start_time);
		$this->db->insert(self::$TABLE);
	}

	public function set_end_time($end_time) {
		// Get last id
		$this->db->select(self::$C_ID);
		$this->db->from(self::$TABLE);
		$this->db->order_by(self::$C_START_TIME, 'DESC');
		$this->db->limit(1);
		$row = $this->db->get()->row();
		$id = $row->id;

		// Update
		$this->db->set(self::$C_END_TIME, $end_time);
		$this->db->where(self::$C_ID, $id);
		$this->db->update(self::$TABLE);
	}
}
