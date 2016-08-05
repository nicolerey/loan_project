<?php

class Banks_model extends CI_Model
{

	protected $table = 'banks';

	public function all()
	{
		$banks_result = $this->db->get($this->table)->result_array();
		if($banks_result){
			foreach ($banks_result as $key => $value) {
				$banks_result[$key]['bank_transactions'] = $this->all_bank_transactions($value['banks_id']);
			}
		}

		return $banks_result;
	}

	public function all_bank_transactions($banks_id = FALSE, $start_date = FALSE, $end_date = FALSE)
	{
		if($banks_id)
			$this->db->where('banks_id', $banks_id);
		if($start_date)
			$this->db->where('bank_transactions_date >=', $start_date);
		if($end_date)
			$this->db->where('bank_transactions_date <=', $end_date);

		$this->db->order_by('bank_transactions_date', 'desc');

		return $this->db->get('bank_transactions')->result_array();
	}

	public function get($banks_id)
	{
		$banks_result = $this->db->get_where($this->table, ['banks_id' => $banks_id])->row_array();
		if($banks_result){
			$banks_result['bank_transactions'] = $this->db->get_where('bank_transactions', ['banks_id' => $banks_id])->result_array();
		}

		return $banks_result;
	}

	public function get_bank_transaction($bank_transactions_id)
	{
		return $this->db->get_where('bank_transactions', ['bank_transactions_id' => $bank_transactions_id])->row_array();
	}

	public function update($banks_id, $data)
	{
		$this->db->where('banks_id', $banks_id);
		return $this->db->update($this->table, $data);
	}

	public function update_bank_transaction($bank_transactions_id, $data)
	{
		$this->db->where('bank_transactions_id', $bank_transactions_id);
		return $this->db->update('bank_transactions', $data);
	}

	public function delete($banks_id)
	{
		return $this->db->delete($this->table, ['banks_id' => $banks_id]);
	}

	public function delete_bank_transaction($bank_transactions_id)
	{
		return $this->db->delete('bank_transactions', ['bank_transactions_id' => $bank_transactions_id]);
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function insert_bank_transaction($data)
	{
		return $this->db->insert('bank_transactions', $data);
	}
}