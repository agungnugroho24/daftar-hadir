<?php

class Service_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function addUser($nama, $key)
	{
		$addUser = $this->db->insert('user', array("name"=>$nama));
		if (!$addUser) {
		  return 0;
		}
		$user_id = $this->db->insert_id();

		$key = array(
		  "user_id" => $user_id,
		  "api_key" => $key,
		  "api_key_activated" => 'yes'
		);
		$add = $this->db->insert('api_keys', $key);
		if (!$add) {
		  return 0;
		}

		return 1;
	}

	public function getUser()
	{
		$query = $this->db->query("
			select * 
			from user s1 
			left join api_keys s2 on s2.user_id=s1.id
		");

		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function setDomain($input)
	{
		$key = array(
		  "domain_name" => $input['nama'],
		  "domain_url" => $input['url'],
		  "domain_alias" => $input['alias'],
		  "domain_auth" => $input['auth'],
		  "domain_key" => $input['key']
		);
		$add = $this->db->insert('domain', $key);
		if (!$add) {
		  return 0;
		}

		return 1;
	}

	public function getDomain()
	{
		$query = $this->db->query("
			select *, group_concat(d2.endpoint_name separator '<br>') endpoint
			from domain d1
			left join endpoint d2 on d2.domain_id=d1.domain_id
			group by d1.domain_id
		");

		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getMethod()
	{
		$query = $this->db->query("select * from method");

		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function setEndpoint($input)
	{
		$key = array(
		  "method_id" => $input['met'],
		  "domain_id" => $input['id'],
		  "endpoint_name" => $input['endpoint']
		);
		$add = $this->db->insert('endpoint', $key);
		if (!$add) {
		  return 0;
		}

		return 1;
	}

}
