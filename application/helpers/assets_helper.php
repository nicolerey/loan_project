<?php

if(!function_exists('css'))
{
	function css($filename)
	{
		$link = base_url("assets/css/{$filename}");
		return "<link href=\"{$link}\" rel=\"stylesheet\"/>";
	}
}

if(!function_exists('script'))
{
	function script($filename)
	{
		$link = base_url("assets/js/{$filename}");
		return "<script type=\"text/javascript\" src=\"{$link}\"></script>";
	}
}

if(!function_exists('images'))
{
	function images($filename, $type)
	{
		$link = base_url("assets/images/{$filename}");
		if($type=="navbar_picture")
			return "<img src=\"{$link}\" alt=\"...\" class=\"img-circle profile_img\">";
		else if($type=="account_picture")
			return "<img src=\"{$link}\" alt=\"\">";
	}
}

if(!function_exists('plugin_script'))
{
	function plugin_script($filename)
	{
		$link = base_url("assets/plugins/{$filename}");
		return "<script type=\"text/javascript\" src=\"{$link}\"></script>";
	}
}

if(!function_exists('plugin_css'))
{
	function plugin_css($filename)
	{
		$link = base_url("assets/plugins/{$filename}");
		return "<link href=\"{$link}\" rel=\"stylesheet\"/>";
	}
}