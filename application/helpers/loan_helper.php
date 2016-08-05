<?php

if(!function_exists('loan_name')){
	function loan_name($loan)
	{
		return $loan['loans_title']." (P ".$loan['loans_amount'].")";
	}
}