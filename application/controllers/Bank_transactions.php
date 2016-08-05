<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Bank_transactions extends HR_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Banks_model' => 'bank']);
	}

	public function index()
	{
		redirect('home');
	}

	public function AddBankTransaction()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$error_msg = [];
			if(!isset($input['bank_transactions_date']) || $input['bank_transactions_date']=="")
				array_push($error_msg, "Transaction date needed.");
			if(!isset($input['banks_id']))
				array_push($error_msg, "Bank is required.");
			if(!isset($input['bank_transactions_type']))
				array_push($error_msg, "Transaction type is needed.");
			if(!isset($input['bank_transactions_amount']))
				array_push($error_msg, "Amount is needed.");

			if(isset($input['bank_transactions_amount']))
				$input['bank_transactions_amount'] = format_decimal($input['bank_transactions_amount']);
			if(empty($error_msg) && $this->bank->insert_bank_transaction($input)){
				$this->output->set_output(json_encode([
					'result' => TRUE,
				]));
				return;
			}

			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => $error_msg
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No information supplied.']
		]));
		return;
	}

	public function GetBankTransactions($type = FALSE)
	{
		$this->output->set_content_type('json');

		if(!$type)
			$bank_transactions = $this->bank->all_bank_transactions();
		else{
			$input = $this->input->post();
			$bank_transactions = $this->bank->all_bank_transactions(NULL, $input['start_date'], $input['end_date']);
		}
		if(!empty($bank_transactions)){
			foreach ($bank_transactions as $key => $value) {
				$bank = $this->bank->get($value['banks_id']);
				$bank_transactions[$key]['banks_name'] = $bank['banks_name'];
			}
			$this->output->set_output(json_encode([
				'data' => $bank_transactions
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'data' => false
		]));
		return;
	}

	public function DeleteBankTransaction()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($this->bank->delete_bank_transaction($input['bank_transactions_id'])){
				$this->output->set_output(json_encode([
					'result' => TRUE
				]));
				return;
			}

			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Something went wrong in the server.']
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No transaction to delete.']
		]));
		return;
	}

	public function UpdateBankTransaction()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$bank_transactions_id = $input['bank_transactions_id'];
			if($input['table_column']=="bank_transactions_amount")
				$input['data'] = format_decimal($input['data']);
			if($this->bank->update_bank_transaction($bank_transactions_id, [$input['table_column'] => $input['data']])){
				$this->output->set_output(json_encode([
					'result' => TRUE
				]));
				return;
			}

			$this->output->set_output(json_encode([
				'result' => FALSE,
				'messages' => ['Error occured in the server.']
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['No bank to edit.']
		]));
		return;
	}
}