<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Customers extends HR_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Customers_model' => 'customer']);
	}

	public function index()
	{
		redirect('home');
	}

	public function AddCustomer()
	{
		$error_msg = [];
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($input['customers_birthdate']=="")
				$input['customers_birthdate'] = date('Y-m-d');
			if(isset($input['customers_nickname']) || isset($input['customers_firstname']) || isset($input['customers_lastname']) || isset($input['customers_middleinitial'])){
				if($this->customer->insert($input)){
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
			else
				array_push($error_msg, "Customer name is required.");

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

	public function GetCustomers()
	{
		$this->output->set_content_type('json');

		$customers = $this->customer->all();
		if(!empty($customers)){
			foreach ($customers as $key => $value){
				$customers[$key]['customers_name'] = customer_name($value);
				$customers[$key]['num_of_loans'] = count($value['loans']);

				$total_loan_amount = 0;
				$total_payed_amount = 0;
				foreach ($value['loans'] as $loan_key => $loan_value) {
					$total_loan_amount += $loan_value['loans_amount'];
					foreach ($loan_value['loan_payments'] as $loan_payment_key => $loan_payment_value) {
						$total_payed_amount += $loan_payment_value['loan_payments_amount'];
					}
				}
				$customers[$key]['total_loan_amount'] = $total_loan_amount;
				$customers[$key]['total_payed_amount'] = $total_payed_amount;
			}

			$this->output->set_output(json_encode([
				'data' => $customers
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'data' => false
		]));
		return;
	}

	public function DeleteCustomer()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			if($this->customer->delete($input['customers_id'])){
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
			'messages' => ['No customer to delete.']
		]));
		return;
	}

	public function UpdateCustomer()
	{
		$this->output->set_content_type('json');

		$input = $this->input->post();
		if($input){
			$customers_id = $input['customers_id'];
			if($this->customer->update($customers_id, [$input['table_column'] => $input['data']])){
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