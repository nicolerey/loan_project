<?php

if(!function_exists('user_id')){
	function user_id()
	{
		$CI =& get_instance();
		return $CI->session->userdata('users_id');
	}
}

if(!function_exists('user_name')){
	function user_name()
	{
		$CI =& get_instance();
		return ucwords($CI->session->userdata('users_name'));
	}
}

if(!function_exists('user_password')){
	function user_password($role)
	{
		$CI =& get_instance();
		return $CI->session->userdata('users_password');
	}	
}

if(!function_exists('format_decimal')){
	function format_decimal($value)
	{
		return floatval(str_replace(',', '', $value));
	}
}