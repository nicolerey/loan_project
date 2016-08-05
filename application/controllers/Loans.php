<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Loans extends HR_Controller 
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

	public function AddLoan()
	{
		$error_msg = [];
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$error_msg = [];
			if(!isset($input['loans_date']) || $input['loans_date']=="")
				array_push($error_msg, "No date supplied.");
			if(!isset($input['customers_id']))
				array_push($error_msg, "No customer selected.");
			if(!isset($input['loans_amount']))
				array_push($error_msg, "No amount supplied.");
			if(!isset($input['loans_interest']))
				array_push($error_msg, "Loan interest is needed.");

			if(isset($input['loans_amount']))
				$input['loans_amount'] = format_decimal($input['loans_amount']);
			if(isset($input['loans_interest']))
				$input['loans_interest'] = format_decimal($input['loans_interest']);

			$input['users_id'] = user_id();
			if(empty($error_msg) && $this->loan->insert($input)){
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

	public function GetLoans($type = FALSE)
	{
		$this->output->set_content_type('json');

		if(!$type)
			$loans = $this->loan->all(user_id());
		else{
			$input = $this->input->post();
			$loans = $this->loan->all(user_id(), NULL, $input['start_date'], $input['end_date']);
		}
		if(!empty($loans)){
			foreach ($loans as $key => $value) {
				$customer = $this->customer->get($value['customers_id']);
				$loans[$key]['customers_name'] = customer_name($customer);
				$loans[$key]['loan_name'] = loan_name($value);
				$loans[$key]['num_of_transactions'] = count($value['loan_payments']);

				$total_payed_amount = 0;
				foreach ($value['loan_payments'] as $loan_payment_key => $loan_payment_value) {
					$total_payed_amount += $loan_payment_value['loan_payments_amount'];
				}
				$loans[$key]['total_payed_amount'] = $total_payed_amount;
			}
			$this->output->set_output(json_encode([
				'data' => $loans
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'data' => false
		]));
		return;
	}

	public function DeleteLoan()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($this->loan->delete($input['loans_id'])){
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
			'messages' => ['No loan to delete.']
		]));
		return;
	}

	public function UpdateLoan()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$loans_id = $input['loans_id'];
			if($input['table_column']=='loans_amount')
				$input['data'] = format_decimal($input['data']);
			if($input['table_column']=='loans_interest')
				$input['data'] = format_decimal($input['data']);
			if($this->loan->update($loans_id, [$input['table_column'] => $input['data']])){
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