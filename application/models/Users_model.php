<?php

class Users_model extends CI_Model
{

	protected $table = 'users';

	public function all($users_id = FALSE, $start_date = FALSE, $end_date = FALSE)
	{
		if($users_id)
			$this->db->where('users_id !=', $users_id);
		if($start_date)
			$this->db->where('users_date_creation <=', $start_date);
		if($end_date)
			$this->db->where('users_date_creation >=', $end_date);

		return $this->db->get($this->table)->result_array();
	}

	public function get($users_id)
	{
		return $this->db->get_where($this->table, ['users_id' => $users_id])->row_array();
	}

	public function update($users_id, $data)
	{
		$this->db->where('users_id', $users_id);
		return $this->db->update($this->table, $data);
	}

	public function authenticate_user($data)
	{
		$data['users_password'] = md5($data['users_password']);
		return $this->db->get_where($this->table, $data)->row_array();
	}
}