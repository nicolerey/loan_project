<?php

class Logout extends HR_Controller
{
	public function index()
	{
		$this->output->set_content_type('json');
		$this->session->sess_destroy();

		$this->output->set_output(json_encode([
			'result' => TRUE
		]));
		return;
	}
}