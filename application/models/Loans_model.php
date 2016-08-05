<?php

class Loans_model extends CI_Model
{

	protected $table = 'loans';

	public function all($users_id = FALSE, $customers_id = FALSE, $start_date = FALSE, $end_date = FALSE)
	{
		if($users_id)
			$this->db->where('users_id', $users_id);
		if($start_date)
			$this->db->where('loans_date >=', $start_date);
		if($end_date)
			$this->db->where('loans_date <=', $end_date);
		if($customers_id)
			$this->db->where('customers_id', $customers_id);

		$this->db->order_by('loans_date', 'desc');
		$loans_result = $this->db->get($this->table)->result_array();
		if($loans_result){
			foreach ($loans_result as $loan_key => $loan_value) {
				$this->db->order_by('loan_payments_date', 'desc');
				$loans_result[$loan_key]['loan_payments'] = $this->db->get_where('loan_payments', ['loans_id' => $loan_value['loans_id']])->result_array();
			}
		}

		return $loans_result;
	}

	public function all_loan_payments($start_date = FALSE, $end_date = FALSE)
	{
		if($start_date)
			$this->db->where('loan_payments_date >=', $start_date);
		if($end_date)
			$this->db->where('loan_payments_date <=', $end_date);

		$this->db->order_by('loan_payments_date', 'desc');

		return $this->db->get('loan_payments')->result_array();
	}

	public function get($loans_id, $customers_id = FALSE)
	{
		$loans_result = $this->db->get_where($this->table, ['loans_id' => $loans_id])->row_array();
		if($loans_result){			
			if($customers_id)
				$this->db->where('customers_id', $customers_id);

			$loans_result['loan_payments'] = $this->db->get_where('loan_payments', ['loans_id' => $loans_id]);
		}

		return $loans_result;
	}

	public function update($loans_id, $data)
	{
		$this->db->where('loans_id', $loans_id);
		$this->db->update($this->table, $data);
		print_r($this->db->last_query());
		return;
	}

	public function update_loan_payments($loan_payments_id, $data)
	{
		$this->db->where('loan_payments_id', $loan_payments_id);
		return $this->db->update('loan_payments', $data);
	}

	public function delete($loans_id)
	{
		return $this->db->delete($this->table, ['loans_id' => $loans_id]);
	}

	public function delete_loan_payments($loan_payments_id)
	{
		return $this->db->delete('loan_payments', ['loan_payments_id' => $loan_payments_id]);
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function insert_loan_payments($data)
	{
		return $this->db->insert('loan_payments', $data);
	}
}