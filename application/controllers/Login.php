<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Users_model' => 'users']);
	}

	public function index()
	{
		if($this->session->userdata('id')){
			redirect('home');
		}
		$this->load->view('login');
	}

	public function LoginAttempt()
	{
		$this->output->set_content_type('json');
		$input = $this->input->post();

		$user = $this->users->authenticate_user($input);
		if($user){
			$this->session->set_userdata($user);
			$this->output->set_output(json_encode([
				'result' => TRUE
			]));
			return;
		}

		$this->output->set_output(json_encode([
			'result' => FALSE,
			'messages' => ['Invalid username/password.']
		]));
		return;
	}
}