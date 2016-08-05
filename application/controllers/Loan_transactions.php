<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Loan_transactions extends HR_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Loans_model' => 'loan', 'Customers_model' => 'customer']);
	}

	public function index()
	{
		redirect('home');
	}

	public function AddLoanTransactions()
	{
		$error_msg = [];
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$error_msg = [];
			if(!isset($input['loan_payments_date']) || $input['loan_payments_date']=="")
				array_push($error_msg, "No date given.");
			if(!isset($input['customers_id']))
				array_push($error_msg, "No customer selected.");
			if(!isset($input['loan_payments_amount']))
				array_push($error_msg, "No amount given.");

			if(isset($input['loan_payments_amount']))
				$input['loan_payments_amount'] = format_decimal($input['loan_payments_amount']);

			if(empty($error_msg) && $this->loan->insert_loan_payments($input)){
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

	public function GetLoanTransactions($type = FALSE)
	{
		$this->output->set_content_type('json');

		if(!$type)
			$loan_transactions = $this->loan->all_loan_payments();
		else{
			$input = $this->input->post();
			$loan_transactions = $this->loan->all_loan_payments($input['start_date'], $input['end_date']);
		}
		if(!empty($loan_transactions)){
			foreach ($loan_transactions as $key => $value) {
				$loan = $this->loan->get($value['loans_id']);
				if($loan)
					$loan_transactions[$key]['loan_name'] = loan_name($loan);

				$customer = $this->customer->get($value['customers_id']);
				if($customer)
					$loan_transactions[$key]['customer_name'] = customer_name($customer);
			}
			$this->output->set_output(json_encode([
				'data' => $loan_transactions
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'data' => false
		]));
		return;
	}

	public function DeleteLoanTransaction()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($this->loan->delete_loan_payments($input['loan_payments_id'])){
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

	public function UpdateLoanTransaction()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){			
			if($input['table_column']=='loan_payments_amount')
				$input['data'] = format_decimal($input['data']);

			$loans_id = $input['loan_payments_id'];
			if($this->loan->update_loan_payments($loans_id, [$input['table_column'] => $input['data']])){
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
			'messages' => ['No customer to edit.']
		]));
		return;
	}
}