<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banks extends HR_Controller 
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

	public function AddBank()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$error_msg = [];
			if(!isset($input['banks_name']))
				array_push($error_msg, "Bank name needed.");

			if(isset($input['banks_balance']))
				$input['banks_balance'] = format_decimal($input['banks_balance']);
			if(empty($error_msg) && $this->bank->insert($input)){
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

	public function GetBanks()
	{
		$this->output->set_content_type('json');

		$banks = $this->bank->all();
		if(!empty($banks)){
			foreach ($banks as $key => $value) {
				$banks[$key]['num_of_transactions'] = count($value['bank_transactions']);

				$total_transaction_amount = 0;
				foreach ($value['bank_transactions'] as $bank_transaction_key => $bank_transaction_value) {
					if($bank_transaction_value['bank_transactions_type']=='d' || $bank_transaction_value['bank_transactions_type']=='i')
						$total_transaction_amount += $bank_transaction_value['bank_transactions_amount'];
					else if($bank_transaction_value['bank_transactions_type']=='w')
						$total_transaction_amount -= $bank_transaction_value['bank_transactions_amount'];
				}
				$banks[$key]['total_transaction_amount'] = $total_transaction_amount;
				$banks[$key]['total_bank_balance'] = $value['banks_balance'] + $total_transaction_amount;
			}
			$this->output->set_output(json_encode([
				'data' => $banks
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'data' => false
		]));
		return;
	}

	public function DeleteBank()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($this->bank->delete($input['banks_id'])){
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

	public function UpdateBank()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$banks_id = $input['banks_id'];
			if($input['table_column']=="banks_balance")
				$input['data'] = format_decimal($input['data']);
			if($this->bank->update($banks_id, [$input['table_column'] => $input['data']])){
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