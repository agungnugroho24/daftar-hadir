<?php

class Api_model extends CI_Model {

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

	public function getUrlByDomain($domain)
	{
		$query = $this->db->query("select * from domain where domain_alias='".$domain."'");

		$arr = $query->row();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getEndpoint($domain_id,$endpoint)
	{
		$query = $this->db->query("
			select d2.method_name,d1.* 
			from endpoint d1
			left join method d2 on d2.method_id=d1.method_id
			where d1.domain_id=$domain_id and d1.endpoint_name='".$endpoint."'");

		$arr = $query->row();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

}
