<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	var $username;
	public function __construct() {
        parent::__construct();
        $this->load->model("admin_model");
        $this->load->library("libcontrol");
        $this->load->library("admin/template");
		$data = $this->libcontrol->getData();
		if(empty($data->data)){
			$this->libcontrol->deleteSession();
			$this->session->sess_destroy();
			header("location: https://akun.bappenas.go.id/bp-um/service/front/rekrutmen/UZVboaakHScU36+UBPUL7LsRc6vDx1PZD2v2PWS4vDygFBMfrDyZ3S+AdrnXeMGhtkABXQhjBuVGFglhGAzr3A==");
			exit();
		}else{
			if ($data->kunci_status=="Success") {
				$userdata = $data->userdata; 
				$this->username = $userdata[0]->user_name;
				$name = $userdata[0]->nama;
				$uke = $userdata[0]->id_unitkerja;
				$check = $this->admin_model->getData("user", "username='$this->username'"); 
				if ($check==0) {
					$this->admin_model->addUserAccount($this->username, $name, $uke);
				}else{
					$this->admin_model->updateUserAccount($this->username, $name, $uke);					
				}
				$this->checkRole();
			}else{
				$this->libcontrol->deleteSession();
				$this->session->sess_destroy();
				header("location: https://akun.bappenas.go.id/bp-um/service/front/rekrutmen/UZVboaakHScU36+UBPUL7LsRc6vDx1PZD2v2PWS4vDygFBMfrDyZ3S+AdrnXeMGhtkABXQhjBuVGFglhGAzr3A==");
			}
		}
    }

    public function logout()
    {
    	$this->libcontrol->deleteSession();
		$this->session->sess_destroy();
		header("location: https://akun.bappenas.go.id/bp-um/service/front/rekrutmen/UZVboaakHScU36+UBPUL7LsRc6vDx1PZD2v2PWS4vDygFBMfrDyZ3S+AdrnXeMGhtkABXQhjBuVGFglhGAzr3A==");
    }

    public function checkRole()
    {
		$data = $this->libcontrol->getData();
		$userdata = $data->userdata; 
		$username = $userdata[0]->user_name;
		$check = $this->admin_model->getData("user", "username='$username'"); 
		if ($check[0]['role']!="1") {
		    $this->libcontrol->deleteSession();
			redirect(base_url());
		}
    }

    public function templating()
    {
		$datauser = $this->libcontrol->getData();
		if(empty($datauser->data)){
			$this->libcontrol->deleteSession();
			$this->session->sess_destroy();
			header("location: https://akun.bappenas.go.id/bp-um/service/front/rekrutmen/UZVboaakHScU36+UBPUL7LsRc6vDx1PZD2v2PWS4vDygFBMfrDyZ3S+AdrnXeMGhtkABXQhjBuVGFglhGAzr3A==");
			exit();
		}else{
			if ($datauser->kunci_status=="Success") {
				$userdata = $datauser->userdata; 
				$username = $userdata[0]->user_name;
				$name = $userdata[0]->nama;
				$uke = $userdata[0]->id_unitkerja;
			}else{
		    	$this->libcontrol->deleteSession();
				$this->session->sess_destroy();
				header("location: https://akun.bappenas.go.id/bp-um/service/front/rekrutmen/UZVboaakHScU36+UBPUL7LsRc6vDx1PZD2v2PWS4vDygFBMfrDyZ3S+AdrnXeMGhtkABXQhjBuVGFglhGAzr3A==");
				exit();
			}
		}
		
		if ($userdata[0]->avatar) {
			$data['avatar'] = $userdata[0]->avatar;
		} else {
			$data['avatar'] = "user.png";
		}
		// $username = "dwi.afif";
		$data['datauser'] = $this->admin_model->getData("user", "username='$username'"); 

    	$data['section']  = "Seleksi JPT";
		$data['head']     = $this->load->view('templates/head', $data, TRUE);
		$data['footer']   = $this->load->view('templates/footer', $data, TRUE);
		$data['navbar']   = $this->load->view('templates/navbar', $data, TRUE);
		$data['sidebar']  = $this->load->view('templates/sidebar', $data, TRUE);
		return $data;
    }


	public function index()
	{
		/*$data = $this->templating();
        $data['content']  = $this->load->view('news/news', $data, TRUE);
      	$this->template->load('home', null, $data);
      	*/
      	$this->news();
	}

	public function news()
	{
		$data = $this->templating();

		$data['news'] = $this->admin_model->getNews();
		$data['content']  = $this->load->view('news/news', $data, TRUE);
      	$this->template->load('home', null, $data);
	}

	public function modalAddNews()
	{
		$this->load->view('news/modalAddNews');
	}

	public function addNews()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$title = $this->input->post("title");
			$content = $this->input->post("content");
			$dataNews = array(
				'news_title' => $title,
				'news_slug' => $this->slug($title.rand(10,1000)),
				'news_content' => $content,
				'news_type' => $this->input->post('type'),
				'created_by' => $this->username
			);
			$idNews = $this->admin_model->inputData('news', $dataNews);

			if ($idNews!="0") {
				$count = count($_FILES['files']['name']);
				if ($count!="0") {
					$judul = $this->input->post("judul");
					// print_r($judul[1]);
					for ($i = 0; $i < $count; $i++) {
						if (!empty($_FILES['files']['name'][$i])) {
							$_FILES['file']['name'] = $_FILES['files']['name'][$i];
							$_FILES['file']['type'] = $_FILES['files']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['files']['error'][$i];
							$_FILES['file']['size'] = $_FILES['files']['size'][$i];


							if (trim($judul[$i])!='') {
								$name = $this->slug($judul[$i]);							
							}else{		
								$name = $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['files']['name'][$i]));		
							}

							$config['upload_path']          = 'file/news';
							$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
							$config['max_size']             = 25000;
							$config['file_name']			= $name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('file')) {
								$uploadData = $this->upload->data();
								$file = $uploadData['file_name'];
							}else{
								return 0;
								exit();
							}

							$isian = array(	
								'news_id'	=> $idNews,
								'news_file_name' => $file,
								'news_file_link' => 'file/news/'
							);
							$idFile = $this->admin_model->inputData("news_file", $isian);
							if ($idFile=="0") {
								return 0;
								exit();
							}
						}
					}	
				}else{
					return 1;
				}
			}else{
				return 0;
			}
			
			return $idNews;
		}else{
			redirect(base_url());
		}		
	}

	public function modalEditNews()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {
				$data['news'] = $this->admin_model->getdata("news", "news_id=".$id);
				$this->load->view('news/modalEditNews', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());
		}
	}

	public function editNews()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$idNews = $this->input->post('news_id');
			if ($idNews!="0") {
				$title = $this->input->post("title");
				$content = $this->input->post("content");
				$dataNews = array(
					'news_title' => $title,
					'news_slug' => $this->slug($title.rand(10,1000)),
					'news_content' => $content,
					'news_type' => $this->input->post('type'),
					'updated_by' => $this->username
				);
				$this->db->where('news_id', $idNews);
				$this->db->update('news', $dataNews);

				$count = count($_FILES['files']['name']);
				$count = count($_FILES['files']['name']);
				if ($count!="0") {
					$judul = $this->input->post("judul");
					// print_r($judul[1]);
					for ($i = 0; $i < $count; $i++) {
						if (!empty($_FILES['files']['name'][$i])) {
							$_FILES['file']['name'] = $_FILES['files']['name'][$i];
							$_FILES['file']['type'] = $_FILES['files']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['files']['error'][$i];
							$_FILES['file']['size'] = $_FILES['files']['size'][$i];


							if (trim($judul[$i])!='') {
								$name = $this->slug($judul[$i]);							
							}else{		
								$name = $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['files']['name'][$i]));		
							}

							$config['upload_path']          = 'file/news';
							$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
							$config['max_size']             = 25000;
							$config['file_name']			= $name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('file')) {
								$uploadData = $this->upload->data();
								$file = $uploadData['file_name'];
							}else{
								return 0;
								exit();
							}

							$isian = array(	
								'news_id'	=> $idNews,
								'news_file_name' => $file,
								'news_file_link' => 'file/news/'
							);
							$idFile = $this->admin_model->inputData("news_file", $isian);
							if ($idFile=="0") {
								return 0;
								exit();
							}
						}
					}	
				}else{
					return 1;
				}
			}else{
				return 0;
			}
			
			return $idNews;
		}else{
			redirect(base_url());
		}		
	}

	public function deleteFile()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$file = $this->admin_model->getdata("news_file", "news_file_id=".$id);
			unlink($file[0]['news_file_link'].$file[0]['news_file_name']);
			$this->db->where('news_file_id', $id);
			$this->db->delete('news_file');
			echo 1;
		}else{
			redirect(base_url());
		}	
	}

	public function deleteNews()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$this->db->where('news_id', $id);
			$this->db->update('news', array("news_delete"=>"1"));
			echo 1;
		}else{
			redirect(base_url());
		}
	}

	public function period()
	{
		$data = $this->templating();

		$data['period'] = $this->admin_model->getdata("period", "period_status=1", "period_id desc");
		$data['content']  = $this->load->view('period/period', $data, TRUE);
      	$this->template->load('home', null, $data);
	}

	public function modalAddPeriod()
	{
		$this->load->view('period/modalAddPeriod');
	}

	public function addPeriod()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$period = $this->admin_model->getdata("period", "YEAR(period_start)=YEAR(CURDATE())", "period_id desc", "1");
			if ($period!="0") {
				$urutan = (int) substr($period[0]['period_code'], 0, 2);
				$urutan++;
				$urut = sprintf("%02s", $urutan).date("Y");
				$name = $this->input->post("name");
				$start = $this->input->post("start");
				$end = $this->input->post("end");
				$dataPeriod = array(
					'period_name' => $name,
					'period_code' => $urut,
					'period_start' => $start,
					'period_end' => $end,
					'created_by' => $this->username
				);
				$idPeriod = $this->admin_model->inputData('period', $dataPeriod);
				
				return $idPeriod;
			}else{
				$name = $this->input->post("name");
				$start = $this->input->post("start");
				$end = $this->input->post("end");
				$dataPeriod = array(
					'period_name' => $name,
					'period_code' => '01'.date("Y"),
					'period_start' => $start,
					'period_end' => $end,
					'created_by' => $this->username
				);
				$idPeriod = $this->admin_model->inputData('period', $dataPeriod);
				
				return $idPeriod;
			}
		}else{
			redirect(base_url());
		}		
	}

	public function modalEditPeriod()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {
				$data['period'] = $this->admin_model->getdata("period", "period_id=".$id);
				$this->load->view('period/modalEditPeriod', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());
		}
	}

	public function editPeriod()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$idPeriod = $this->input->post('period_id');
			if ($idPeriod!="0") {
				$name = $this->input->post("name");
				$start = $this->input->post("start");
				$end = $this->input->post("end");
				$dataPeriod = array(
					'period_name' => $name,
					'period_start' => $start,
					'period_end' => $end,
					'updated_by' => $this->username
				);
				$this->db->where('period_id', $idPeriod);
				$this->db->update('period', $dataPeriod);
				
				return $idPeriod;				
			}else{
				return 0;
			}
		}else{
			redirect(base_url());
		}		
	}

	public function deletePeriod()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$dataPeriod = array(
				'period_status' => '0',
				'updated_by' => $this->username
			);
			$this->db->where('period_id', $id);
			$this->db->update('period', $dataPeriod);
			echo 1;
		}else{
			redirect(base_url());
		}
	}

	public function vacancy()
	{
		$data = $this->templating();

		$data['vacancy'] = $this->admin_model->getVacancy();
		$data['content']  = $this->load->view('vacancy/vacancy', $data, TRUE);
      	$this->template->load('home', null, $data);
	}

	public function modalAddVacancy()
	{
		$data['period'] = $this->admin_model->getdata("period", "curdate() <= period_end and period_status=1");
		$this->load->view('vacancy/modalAddVacancy', $data);
	}

	public function addVacancy()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$dataNews = array(
				'period_id' => $this->input->post('period'),
				'vacancy_name' => $this->input->post('name'),
				'vacancy_slug' => $this->slug($this->input->post('name').rand(10,1000)),
				'vacancy_type' => $this->input->post('type'),
				'vacancy_detail' => $this->input->post('content'),
				'created_by' => $this->username
			);
			$idVacancy = $this->admin_model->inputData('vacancy', $dataNews);

			if ($idVacancy!="0") {
				$count = count($_FILES['files']['name']);
				$count = count($_FILES['files']['name']);
				if ($count!="0") {
					$judul = $this->input->post("judul");
					// print_r($judul[1]);
					for ($i = 0; $i < $count; $i++) {
						if (!empty($_FILES['files']['name'][$i])) {
							$_FILES['file']['name'] = $_FILES['files']['name'][$i];
							$_FILES['file']['type'] = $_FILES['files']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['files']['error'][$i];
							$_FILES['file']['size'] = $_FILES['files']['size'][$i];


							if (trim($judul[$i])!='') {
								$name = $this->slug($judul[$i]);							
							}else{		
								$name = $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['files']['name'][$i]));		
							}

							$config['upload_path']          = 'file/vacancy';
							$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
							$config['max_size']             = 25000;
							$config['file_name']			= $name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('file')) {
								$uploadData = $this->upload->data();
								$file = $uploadData['file_name'];
							}else{
								return 0;
								exit();
							}

							$isian = array(	
								'vacancy_id'	=> $idVacancy,
								'vacancy_file_name' => $file,
								'vacancy_file_link' => 'file/vacancy/'
							);
							$idFile = $this->admin_model->inputData("vacancy_file", $isian);
							if ($idFile=="0") {
								return 0;
								exit();
							}
						}
					}	
				}else{
					return 1;
				}
			}else{
				return 0;
			}
			
			return $idVacancy;
		}else{
			redirect(base_url());
		}		
	}

	public function editVacancy()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$idVacancy = $this->input->post('vacancy_id');
			if ($idVacancy!="0") {				
				$dataVacancy = array(
					'period_id' => $this->input->post('period'),
					'vacancy_name' => $this->input->post('name'),
					'vacancy_slug' => $this->slug($this->input->post('name').rand(10,1000)),
					'vacancy_type' => $this->input->post('type'),
					'vacancy_detail' => $this->input->post('content'),
					'vacancy_status' => $this->input->post('status'),
					'updated_by' => $this->username
				);
				$this->db->where('vacancy_id', $idVacancy);
				$this->db->update('vacancy', $dataVacancy);

				$count = count($_FILES['files']['name']);
				$count = count($_FILES['files']['name']);
				if ($count!="0") {
					$judul = $this->input->post("judul");
					// print_r($judul[1]);
					for ($i = 0; $i < $count; $i++) {
						if (!empty($_FILES['files']['name'][$i])) {
							$_FILES['file']['name'] = $_FILES['files']['name'][$i];
							$_FILES['file']['type'] = $_FILES['files']['type'][$i];
							$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
							$_FILES['file']['error'] = $_FILES['files']['error'][$i];
							$_FILES['file']['size'] = $_FILES['files']['size'][$i];


							if (trim($judul[$i])!='') {
								$name = $this->slug($judul[$i]);							
							}else{		
								$name = $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['files']['name'][$i]));		
							}

							$config['upload_path']          = 'file/vacancy';
							$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
							$config['max_size']             = 25000;
							$config['file_name']			= $name;

							$this->load->library('upload', $config);
							$this->upload->initialize($config);

							if ($this->upload->do_upload('file')) {
								$uploadData = $this->upload->data();
								$file = $uploadData['file_name'];
							}else{
								return 0;
								exit();
							}

							$isian = array(	
								'vacancy_id'	=> $idVacancy,
								'vacancy_file_name' => $file,
								'vacancy_file_link' => 'file/vacancy/'
							);
							$idFile = $this->admin_model->inputData("vacancy_file", $isian);
							if ($idFile=="0") {
								return 0;
								exit();
							}
						}
					}	
				}else{
					return 1;
				}
				
				return $idVacancy;
			}else{
				return 0;
			}
		}else{
			redirect(base_url());
		}		
	}

	public function modalEditVacancy()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {
				$data['period'] = $this->admin_model->getdata("period", "curdate() <= period_end and period_status=1");
				$data['vacancy'] = $this->admin_model->getdata("vacancy", "vacancy_id=".$id);
				$this->load->view('vacancy/modalEditVacancy', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());
		}
	}

	public function deleteFileVacancy()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$file = $this->admin_model->getdata("vacancy_file", "vacancy_file_id=".$id);
			unlink($file[0]['vacancy_file_link'].$file[0]['vacancy_file_name']);
			$this->db->where('vacancy_file_id', $id);
			$this->db->delete('vacancy_file');
			echo 1;
		}else{
			redirect(base_url());
		}	
	}

	public function deleteVacancy()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$this->db->where('vacancy_id', $id);
			$this->db->update('vacancy', array("vacancy_delete"=>"1"));
			echo 1;
		}else{
			redirect(base_url());
		}
	}

	public function list()
	{
		$data = $this->templating();

		$data['pelamar'] = $this->admin_model->getListPelamar();
		$data['content']  = $this->load->view('lamaran/pelamar', $data, TRUE);
      	$this->template->load('home', null, $data);
	}

	public function modalStatus()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {			
				$data['id'] = $id;	
				$data['status'] = $this->admin_model->getdata("status_pelamar");
				$this->load->view('lamaran/modalPelamar', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());			
		}
	}

	public function setStatusPelamar()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {			
				$this->db->where('lamaran_id', $id);
				$this->db->update('lamaran', array("status_id"=>$this->input->post('status'), "note"=>$this->input->post('note')));
				echo 1;
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());			
		}
	}

	public function detail($id=0)
	{
		if ($id!=0) {
			$data = $this->templating();

			$data['pelamar'] = $this->admin_model->getPelamarById($id);
			$data['content'] = $this->load->view('lamaran/detail', $data, TRUE);
	      	$this->template->load('home', null, $data);
		}else{
			redirect(base_url());				
		}
	}

	public function modalReset()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {			
				$data['id'] = $id;	
				$this->load->view('lamaran/modalReset', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());			
		}
	}

	public function reset()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$password = hash('sha512', $this->input->post("password"));	

			$this->db->update('pelamar', array('password' => $password), "pelamar_id = ".$this->input->post("id"));
			echo 1;
		}else{
			redirect(base_url());				
		}
	}
	function export_excel(){
		$data = $this->templating();

		$pelamar = $this->admin_model->getListPelamar();
		if($pelamar != 0){
			foreach($pelamar as $pl){
				$hasil[] = array(	'noreg'	=> $pl['no_registrasi'],
									'nama'	=> $pl['name'],
									'email'	=> $pl['email'],
									'nip'	=> "@".$pl['nip'],
									'ttl'	=> $pl['tmp_lahir']." ,".date('d-m-Y',strtotime($pl['tgl_lahir'])),
									'hp'	=> $pl['hp'],
									'cur_jab' => $pl['jabatan'],
									'cur_uke' => $pl['uke'],
									'cur_ins' => $pl['instansi'],
									'periode' => $pl['period_name'],
									'vac_name'=> $pl['vacancy_name'],
									'vac_type'=> $pl['vacancy_type'],
									'status'  => $pl['status_name'],
									'sub_date'=> $pl['submitdate']);
			}
			$this->load->library("excel");
			$object = new PHPExcel();
			$object->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
			$object->setActiveSheetIndex(0);
			$table_columns	 = array("No Registrasi", "Nama", "Email", "NIP", "T T L", "HP", "Jabatan Saat Ini", "Unitkerja Saat Ini", "Instansi Saat Ini", "Periode Lowongan", "Lowongan Jabatan", "Jenis Jabatan", "Status Lamaran", "Tanggal Submit Lamaran");
			$column = 0;
			foreach($table_columns as $field){
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
				$column++;
			}
			$excel_row = 2;
			foreach($hasil as $row){
				$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['noreg']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['nama']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['email']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['nip']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['ttl']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['hp']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['cur_jab']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['cur_uke']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row['cur_ins']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row['periode']);
					
				$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row['vac_name']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row['vac_type']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row['status']);
				$object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row['sub_date']);
					
				$excel_row++;
			}
			$object->getActiveSheet()->getStyle('A1:Z1')->getFont()->setBold(true);
			foreach(range('A','Y') as $columnID){
				$object->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
			if (ob_get_contents()) ob_end_clean();
			// We'll be outputting an excel file
			$currdate = date('YmdHis');
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Rekapitulasi Informasi Pelamar '.$currdate.'.xls"');
			$object_writer->save('php://output');
		}
	}

	function slug($text){
	    // replace non letter or digits by -
	    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
	    // trim
	    $text = trim($text, '-');
	    // transliterate
	    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
	    // lowercase
	    $text = strtolower($text);
	    // remove unwanted characters
	    $text = preg_replace('~[^-\w]+~', '', $text);	 
	    if (empty($text))
	    {
	        return 'n-a';
	    }
	 
	    return $text;
	}

	public function template_file()
	{
		$data = $this->templating();

		$data['template'] = $this->admin_model->getdata('template_file');
		$data['content']  = $this->load->view('template_file/template', $data, TRUE);
      	$this->template->load('home', null, $data);
	}



	public function modalAddTemplate()
	{
		$this->load->view('template_file/modalAddTemplate');
	}

	public function addTemplate()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){	
			if (!empty($_FILES['file_template']['name']) && $this->input->post('type')!="0" && $this->input->post('name')!="") {
				$_FILES['file']['name'] = $_FILES['file_template']['name'];
				$_FILES['file']['type'] = $_FILES['file_template']['type'];
				$_FILES['file']['tmp_name'] = $_FILES['file_template']['tmp_name'];
				$_FILES['file']['error'] = $_FILES['file_template']['error'];
				$_FILES['file']['size'] = $_FILES['file_template']['size'];

				$config['upload_path']          = 'file/template';
				$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
				$config['max_size']             = 25000;
				$config['file_name']			= $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['file_template']['name']));

				$this->load->library('upload', $config);
				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$file = $uploadData['file_name'];
				}else{
					$error = array('error' => $this->upload->display_errors());
					echo strip_tags($error['error']);
					exit();
				}

				$data = array(
					'template_type' => $this->input->post('type'),
					'template_file_name' => $this->input->post('name'),
					'template_file_link' => 'file/template/'.$file
				);
				$idTemplate = $this->admin_model->inputData('template_file', $data);

				if ($idTemplate=="0") {
					echo 0;
				}else{
					echo 1;
				}
			}else{
				echo "Mohon lengkapi data";				
			}	
		}else{
			redirect(base_url());
		}		
	}

	public function modalEditTemplate()
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('id');
			if ($id) {
				$data['template'] = $this->admin_model->getdata("template_file", "template_file_id=".$id);
				$this->load->view('template_file/modalEditTemplate', $data);
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());
		}
	}

	public function editTemplate()
	{

		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post('template_file_id');
			if ($id!="0") {				
				if ($this->input->post('type')!="0" && $this->input->post('name')!="") {
					if (!empty($_FILES['file_template']['name'])) {
						$_FILES['file']['name'] = $_FILES['file_template']['name'];
						$_FILES['file']['type'] = $_FILES['file_template']['type'];
						$_FILES['file']['tmp_name'] = $_FILES['file_template']['tmp_name'];
						$_FILES['file']['error'] = $_FILES['file_template']['error'];
						$_FILES['file']['size'] = $_FILES['file_template']['size'];

						$config['upload_path']          = 'file/template';
						$config['allowed_types']        = 'jpg|jpeg|png|pdf|PDF|ppt|pptx|doc|docx|xls|xlsx';
						$config['max_size']             = 25000;
						$config['file_name']			= $this->slug(preg_replace('/\\.[^.\\s]{3,4}$/', '', $_FILES['file_template']['name']));

						$this->load->library('upload', $config);
						$this->upload->initialize($config);

						if ($this->upload->do_upload('file')) {
							$uploadData = $this->upload->data();
							$file = $uploadData['file_name'];
							$dataFile = $this->admin_model->getdata("template_file", "template_file_id=".$id);
							unlink($dataFile[0]['template_file_link']);
						}else{
							$error = array('error' => $this->upload->display_errors());
							echo strip_tags($error['error']);
							exit();
						}

						$data = array(
							'template_type' => $this->input->post('type'),
							'template_file_name' => $this->input->post('name'),
							'template_file_link' => 'file/template/'.$file
						);
						$this->db->where('template_file_id', $id);
						$this->db->update('template_file', $data);	

						echo 1;
					}else{
						$data = array(
							'template_type' => $this->input->post('type'),
							'template_file_name' => $this->input->post('name')
						);
						$this->db->where('template_file_id', $id);
						$this->db->update('template_file', $data);	
						
						echo 1;
					}
				}else{
					echo "Mohon lengkapi data";				
				}				
			}else{
				echo 0;
			}
		}else{
			redirect(base_url());
		}		
	}

	public function deleteTemplate()
	{		
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id = $this->input->post("id");
			$file = $this->admin_model->getdata("template_file", "template_file_id=".$id);
			unlink($file[0]['template_file_link']);
			$this->db->where('template_file_id', $id);
			$this->db->delete('template_file');
			echo 1;
		}else{
			redirect(base_url());
		}
	}

	public function visitor()
	{
		$data = $this->templating();

		$data['visitor'] = $this->admin_model->getVisitor();
		$data['content']  = $this->load->view('visitor/chart', $data, TRUE);
      	$this->template->load('home', null, $data);
	}
	
}
