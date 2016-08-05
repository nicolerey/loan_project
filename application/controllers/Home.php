<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends HR_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(['Users_model' => 'users']);
	}

	public function index()
	{
		$this->load->view('frame');
	}
}