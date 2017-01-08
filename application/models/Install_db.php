<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Install_db extends CI_Model {
	private static $T_AGENDA_ITEMS = 'agenda_items';
	private static $T_AGENDA = 'agenda';

	private static $C_START_TIME = 'start_time';

	private static $C_ID = 'id';
	private static $C_DESCRIPTION = '`description`';
	private static $C_ORDER = '`order`';
	private static $C_TIME_DEFAULT = '`time_default`';
	private static $C_IS_TIME_EDITABLE = '`is_time_editable`';
	private static $C_IS_TIME_EXTRA = '`is_time_extra`';
	private static $C_TIME = '`time`';
	private static $C_IS_ALL_PARTICIPANTS = '`is_all_participants`';

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function is_installed() {
		return $this->db->table_exists(self::$T_AGENDA) && 
			$this->db->table_exists(self::$T_AGENDA_ITEMS);
	}

	public function install() {
		if (!$this->db->table_exists(self::$T_AGENDA)) {
			$this->install_agenda_table();
		}
		if (!$this->db->table_exists(self::$T_AGENDA_ITEMS)) {
			$this->install_agenda_items_table();
			$this->populate_agenda_items();
		}
	}

	public function reset_items() {
		$this->db->truncate(self::$T_AGENDA_ITEMS);
		$this->populate_agenda_items();
	}

	private function install_agenda_table() {
		$this->dbforge->add_field(self::$C_ID);
		$this->dbforge->add_field(self::$C_START_TIME . ' int(10) unsigned DEFAULT NULL');
		$this->dbforge->add_key(self::$C_ID);
		$this->dbforge->create_table(self::$T_AGENDA);
	}

	private function install_agenda_items_table() {
		$this->dbforge->add_field(self::$C_ID);
		$this->dbforge->add_field(self::$C_DESCRIPTION . ' text NOT NULL');
		$this->dbforge->add_field(self::$C_ORDER . ' tinyint(4) NOT NULL');
		$this->dbforge->add_field(self::$C_TIME_DEFAULT . ' smallint(6) NOT NULL');
		$this->dbforge->add_field(self::$C_IS_TIME_EDITABLE . ' tinyint(1) NOT NULL DEFAULT 1');
		$this->dbforge->add_field(self::$C_IS_TIME_EXTRA . " tinyint(1) NOT NULL DEFAULT 0 COMMENT 'If this is the extra time field'");
		$this->dbforge->add_field(self::$C_TIME . ' smallint(6) NOT NULL');
		$this->dbforge->add_field(self::$C_IS_ALL_PARTICIPANTS . ' tinyint(1) NOT NULL DEFAULT 0');
		$this->dbforge->add_key(self::$C_ID);
		$this->dbforge->create_table(self::$T_AGENDA_ITEMS);
	}

	private function populate_agenda_items() {
		$data = array(
			array(
				self::$C_ID => 1,
				self::$C_DESCRIPTION => 'Welcome, gain clarity and inspiration',
				self::$C_ORDER => 1,
				self::$C_TIME_DEFAULT => 60,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 60,
				self::$C_IS_ALL_PARTICIPANTS => 0
			),
			array(
				self::$C_ID => 2,
				self::$C_DESCRIPTION => 'Share what\'s new and good',
				self::$C_ORDER => 2,
				self::$C_TIME_DEFAULT => 60,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 60,
				self::$C_IS_ALL_PARTICIPANTS => 1
			),
			array(
				self::$C_ID => 3,
				self::$C_DESCRIPTION => 'Negotiate for time',
				self::$C_ORDER => 3,
				self::$C_TIME_DEFAULT => 20,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 20,
				self::$C_IS_ALL_PARTICIPANTS => 1
			),
			array(
				self::$C_ID => 4,
				self::$C_DESCRIPTION => '
					<ul>
						<li>Last week’s action(s)</li>
						<li>Accountability for last week’s action(s)</li>
						<li><ul>
							<li>If complete, move on</li>
							<li>If incomplete, exploration of challenges/reasons</li>
							<li>Recommit to this action or change</li>
						</ul></li>
						<li>Discussion of challenges/brainstorm</li>
						<li>State actions for the next week (recorded in OneNote)</li>
					</ul>',
				self::$C_ORDER => 4,
				self::$C_TIME_DEFAULT => 540,
				self::$C_IS_TIME_EDITABLE => 1,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 540,
				self::$C_IS_ALL_PARTICIPANTS => 1
			),
			array(
				self::$C_ID => 5,
				self::$C_DESCRIPTION => 'Available extra time',
				self::$C_ORDER => 5,
				self::$C_TIME_DEFAULT => 0,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 1,
				self::$C_TIME => 0,
				self::$C_IS_ALL_PARTICIPANTS => 0
			),
			array(
				self::$C_ID => 6,
				self::$C_DESCRIPTION => 'Make a commitment to stretch. Declare one action for the week that you wouldn\'t take, if not for the group (not necessarily related to business)',
				self::$C_ORDER => 6,
				self::$C_TIME_DEFAULT => 60,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 60,
				self::$C_IS_ALL_PARTICIPANTS => 1
			),
			array(
				self::$C_ID => 7,
				self::$C_DESCRIPTION => 'Gratitude',
				self::$C_ORDER => 7,
				self::$C_TIME_DEFAULT => 20,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 20,
				self::$C_IS_ALL_PARTICIPANTS => 1
			),
			array(
				self::$C_ID => 8,
				self::$C_DESCRIPTION => 'Closing',
				self::$C_ORDER => 8,
				self::$C_TIME_DEFAULT => 15,
				self::$C_IS_TIME_EDITABLE => 0,
				self::$C_IS_TIME_EXTRA => 0,
				self::$C_TIME => 15,
				self::$C_IS_ALL_PARTICIPANTS => 0
			)
		);
		$this->db->insert_batch(self::$T_AGENDA_ITEMS, $data);
	}
}