<?php
/*
	coded by ahmad.osoep@gmail.com
	15 Nov 2018
*/

class Encode{
	
	function __construct()	{
		$this->ci =& get_instance();
		$this->ci->load->library('encryption');
	}
	function encoded($string){
		$ret = strtr($this->ci->encryption->encrypt($string), array('+' => '.', '=' => '-', '/' => '~'));
		return $ret;
	}
	function decode($string){
		$cod = strtr($string, array('.' => '+', '-' => '=', '~' => '/'));
		return $this->ci->encryption->decrypt($cod);
	}
}
?>