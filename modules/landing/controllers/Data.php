<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 This script was created by Ahmad Bakhruddin Yusuf
	 ahmad.osoep@gmail.com
	 */

	public function __construct() {
        parent::__construct();
        $this->load->library("libcontrol");
        $this->load->library("template");
        $this->load->library("encode");
        $this->load->model("admin/admin_model");
        $this->load->model("data_model");
        date_default_timezone_set("Asia/Jakarta");
    }

    public function visitor()
    {
    	$ip    = $this->input->ip_address();
    	$date  = date("Y-m-d"); 
		  
		$app = 'jpt';
		$check = $this->admin_model->getData("visitor", "ip='".$ip."' and date=curdate() and app='".$app."'");
		if($check == 0){
			$data = array(
				'ip' => $ip,
				'app' => $app,
				'date' => $date
			);
			$this->admin_model->inputData('visitor', $data);
		}else{
			$this->db->query("UPDATE visitor SET hits=hits+1 WHERE ip='".$ip."' AND date='".$date."' and app='".$app."'");
		}
    }

    public function templating()
    {

    	$data['section']  = "Daftar Hadir Bappenas";
		$data['head']     = $this->load->view('templates/head', $data, TRUE);
		$data['footer']   = $this->load->view('templates/footer', $data, TRUE);
		$data['navbar']   = $this->load->view('templates/navbar', $data, TRUE);
		return $data;
    }

	public function templating2()
    {

    	$data['section']  = "Daftar Hadir Bappenas";
		$data['head']     = $this->load->view('templates/head', $data, TRUE);
		$data['footer']   = $this->load->view('templates/footer2', $data, TRUE);
		$data['navbar']   = $this->load->view('templates/navbar', $data, TRUE);
		return $data;
    }


	public function index()
	{
		$data = $this->templating();
		
		$start =  date('Y-m-d')." 00:00:00";
		$end   =  date('Y-m-d')." 23:59:00";
	
		if($this->session->userdata('role')== "1"){
			$cond = "start_date > '$start' and end_date <= '$end' and status = 'Active'";
			$data['rapat'] = $this->admin_model->getData("agenda", $cond, "idagenda desc"); 
		}elseif($this->session->userdata('role')== "2"){
			$creator= $this->session->userdata('username');
			$cond 	= "start_date > '$start' and end_date <= '$end' and creator = '$creator' and status = 'Active'";
			$data['rapat'] = $this->admin_model->getData("agenda", $cond, "idagenda desc"); 
		}else{
			$data['rapat'] = "";
		}
		$data['content']  = $this->load->view('content/landing', $data, TRUE);
      	$this->template->load('home', null, $data);
	}
	public function uri($data = ""){
		if($data != ""){
			$cond	= "alias = '$data' and status = 'Active'";
			$agenda	= $this->admin_model->getData("agenda", $cond);
			if($agenda){
				$id = $this->encode->encoded($agenda[0]['idagenda']);
				redirect('form/'.$id);
			}else{
				redirect(base_url());
			}
		}else{
			echo "No data";
		}
		exit;
	}
	public function form($data=""){
		if($data != ""){
			$id		= $this->encode->decode($data);
			$agenda = $this->admin_model->getData("agenda", "idagenda = '$id'");
			if($agenda){
				//print_r($agenda);exit;
				$content = $this->templating2();
				$content['rapat']	= $agenda;
				$content['date']	= date('Y-m-d H:i:s');
				$content['idagenda']= $data;
				$content['form']	= $this->admin_model->getData("form", "idagenda = '$id' and status = 'Active'");
				$content['uke']		= json_decode(file_get_contents("https://bsdm.bappenas.go.id/app/service/api/unitkerja?id=all"));
				$content['content']= $this->load->view('content/form', $content, TRUE);
				$this->template->load('home', null, $content);
			}else{
				echo "No Data";
				exit;
			}
		}else{
			echo "No Data";
			exit;
		}
	}
	public function sendData(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$id		= $this->encode->decode($this->input->post('agenda'));
			$agenda = $this->admin_model->getData("agenda", "idagenda = '$id'");
			$custom	= $this->input->post('custom');
			
			if($agenda){
				if($custom == "true"){
					$form	 = $this->admin_model->getData("form", "idagenda = '$id'", "idform asc");
					foreach($form as $fr){
						$isian[$fr['nameform']] = $this->input->post($fr['nameform']);
					}
					$isian['sign']	= $this->input->post('signdata');
					$content	= serialize($isian);
					$isiandata	= array( 'idagenda'	=> $id,
										 'custom'	=> $content,
										 'date_create'	=> date('Y-m-d H:i:s'));
					$db = $this->admin_model->addData($isiandata, "kehadiran");
				}else{
					$isian = array(	'idagenda'	=> $id,
									'nama'		=> $this->input->post('nama'),
									'jabatan'	=> $this->input->post('jabatan'),
									'uke'		=> $this->input->post('uke'),
									'email'		=> $this->input->post('email'),
									'phone'		=> $this->input->post('nohp'),
									'sign'		=> $this->input->post('signdata'),
									'date_create'=> date('Y-m-d H:i:s'));
				
					$db = $this->admin_model->addData($isian, "kehadiran");
				}
				echo json_encode($db);
			}
		}else{
			redirect(base_url());
		}
	}
}
?>