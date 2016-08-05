<?php

if(!function_exists('customer_name')){
	function customer_name($customer)
	{
		if($customer['customers_firstname'] || $customer['customers_middleinitial'] || $customer['customers_lastname'])
			return join(" ", [$customer['customers_firstname'], $customer['customers_middleinitial'], $customer['customers_lastname']]);
		else if($customer['customers_nickname'])
			return $customer['customers_nickname'];
		else
			return "";
	}
}