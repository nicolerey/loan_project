<?php

class Customers_model extends CI_Model
{

	protected $table = 'customers';

	public function all($start_date = FALSE, $end_date = FALSE)
	{
		$this->load->model(['Loans_model' => 'loan']);

		if($start_date)
			$this->db->where('customers_date_creation <=', $start_date);
		if($end_date)
			$this->db->where('customers_date_creation >=', $end_date);

		$customers = $this->db->get($this->table)->result_array();
		if($customers){
			foreach ($customers as $key => $value) {
				$customers[$key]['loans'] = $this->loan->all(user_id(), $value['customers_id']);
			}
		}

		return $customers;
	}

	public function get($customers_id)
	{
		return $this->db->get_where($this->table, ['customers_id' => $customers_id])->row_array();
	}

	public function update($customers_id, $data)
	{
		$this->db->where('customers_id', $customers_id);
		return $this->db->update($this->table, $data);
	}

	public function delete($customers_id)
	{
		return $this->db->delete($this->table, ['customers_id' => $customers_id]);
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}
}