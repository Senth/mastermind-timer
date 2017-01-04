<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AgendaItem {
	private id;
	private description;
	private order;
	private time;
	private time_default;
	private mIsTimeEditable;
	private mIsTimeExtra;
	private mIsAllParticipants;

	public function __construct($id, $description, $order, $time, $time_default, $time_editable, $time_extra, $all_participants) {
		$this->id = $id;
		$this->description = $description;
		$this->order = $order;
		$this->time = $time;
		$this->time_default = $time_default;
		$this->mIsTimeEditable = $time_editable;
		$this->mIsTimeExtra = $time_extra;
		$this->mIsAllParticipants = $all_participants;
	}

	public get_id() {
		return $this->id;
	}

	public get_description() {
		return $this->description;
	}

	public get_order() {
		return $this->order;
	}

	public get_time() {
		return $this->time;
	}

	public get_time_default() {
		return $this->time_default;
	}

	public is_time_editable() {
		return $this->mIsTimeEditable;
	}

	public is_time_extra() {
		return $this->mIsTimeExtra;
	}

	public is_all_participants() {
		return $this->mIsAllParticipants;
	}
}
