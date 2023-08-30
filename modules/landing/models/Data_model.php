<?php

class Data_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function getPelamarById($id)
	{
		$query = $this->db->query("
			select *
			from pelamar pl
			left join jabatan jb on jb.pelamar_id=pl.pelamar_id
			left join pangkat pk on pk.pangkat_id=jb.pangkat_id
			left join pendidikan pd on pd.pelamar_id=pl.pelamar_id
			left join strata st on st.strata_id=pd.strata_id
			where 
			pl.pelamar_id=$id
		");

		$arr = $query->row_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getDataPendukung($pelamar_id)
	{
		$query = $this->db->query("
			select *, dt.doc_type_id id
			from doc_type dt
			left join doc_pelamar dp on dp.doc_type_id=dt.doc_type_id and dp.pelamar_id=$pelamar_id
		");

		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getVacancyUser($pelamar_id)
	{
		$query = $this->db->query("
			select *
			from lamaran lm
			left join vacancy vc on vc.vacancy_id=lm.vacancy_id
			where
			lm.pelamar_id=$pelamar_id
		");

		$arr = $query->row_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getListVacancy()
	{
		$query = $this->db->query("
			select *, vc.vacancy_id id
			from vacancy vc
			left join period pr on pr.period_id=vc.period_id
			where
			vc.vacancy_status=1
			and vc.vacancy_delete=0
			and pr.period_status=1
			and curdate() <= pr.period_end
		");

		$arr = $query->result_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

	public function getActiveBatch()
	{
		$query = $this->db->query("
			select *
			from period
			where curdate() <= period_end
		");

		$arr = $query->row_array();
		if ($query->num_rows()>0) {
			return $arr;
		}else{
			return 0;			
		}
	}

}
