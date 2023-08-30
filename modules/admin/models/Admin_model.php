<?php

class Admin_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function addData($data, $table)
	{
		$query = $this->db->insert($table, $data);
		
		if ($query) {
			return 1;
		}else{
			return 0;
		}
	}

	public function updateData($data, $cond, $table)
	{
		$this->db->where($cond);
		$query = $this->db->update($table, $data);
		if ($query) {
			return 1;
		}else{
			return 0;
		}
	}

	function getdata($table,$where='',$order='',$limit=''){
		$this->db->select('*');
		if($where != ''){
			$this->db->where($where);
		}
		if ($order!='') {
			$this->db->order_by($order);
		}
		if ($limit!='') {
			$this->db->limit($limit);
		}
		$query = $this->db->get($table);
		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}
	function check_kehadiran_byidform($idform){
		$query = $this->db->query("SELECT z.*
									from form a, agenda x, kehadiran z
									where a.idagenda=x.idagenda and x.idagenda=z.idagenda
									and a.idform='$idform'");
		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}
	function inputData($table, $data){
		$add = $this->db->insert($table, $data);
		if (!$add) {
		  return 0;
		}

		return $this->db->insert_id();
	}


}
