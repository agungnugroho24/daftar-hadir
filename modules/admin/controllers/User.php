<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	/**
	 This script was created by Ahmad Bakhruddin Yusuf
	 ahmad.osoep@gmail.com
	 */
	
	public function __construct() {
        parent::__construct();
        $this->load->model("admin_model");
        $this->load->library("libcontrol");
        $this->load->library("template");
		date_default_timezone_set("Asia/Jakarta");
		
		if($this->session->userdata('role') != "1"){
			redirect('admin/logout');
		}
	}
	
	public function index(){
		$this->showUsers();
	}
	public function templating(){
		$username = $this->session->userdata('username');
		$data['datauser'] = $this->admin_model->getData("users", "username='$username'"); 

    	$data['section']  = "Admin Panel";
		$data['head']     = $this->load->view('templates/head', $data, TRUE);
		$data['footer']   = $this->load->view('templates/footer', $data, TRUE);
		$data['navbar']   = $this->load->view('templates/navbar', $data, TRUE);
		$data['sidebar']  = $this->load->view('templates/sidebar', $data, TRUE);
		return $data;
	}
	public function showUsers(){
		$data = $this->templating();
		
		$data['list_uke'] = json_decode(file_get_contents("https://bsdm.bappenas.go.id/app/service/api/unitkerja?id=all"));
		$data['content']  = $this->load->view('users/datatable', $data, TRUE);
      	$this->template->load('home', null, $data);
	}
	public function users_json(){
		$users = $this->admin_model->getData("users", "status = 'Active'", "iduser desc");
		if($users){
			$i=0;
			foreach ($users as $us) {
				$i++; $iduser = $us['iduser'];
				$btn = '
						<button id="Edit User" type="button" class="btn btn-info btn-sm" onclick=edituser('.$iduser.') data-toggle="modal" data-target="#modalUser"><i class="fa fa-edit"></i> Edit</button>
						<button type="button" class="btn btn-danger btn-sm" onclick=deluser('.$iduser.')><i class="fa fa-times-circle"></i> Hapus</button>
						';
				// $btn = '
				// 		<a class="btn btn-info btn-xs" onclick=edituser('.$iduser.')><i class="fa fa-edit"></i>Edit</a>
				// 		<a class="btn btn-danger btn-xs"  onclick=deluser('.$iduser.')><i class="fa fa-times-circle"></i>Hapus</a>
				// 		';
				
				if($us['role'] == "1"){
					$role = "Super Admin";
				}else{
					$role = "Admin Uke";
				}
				
				$hasil[] = array(	'no' 		=> $i,
									'nama'		=> $us['nama'],
									'uke'		=> $us['uke'],
									'role'		=> $role,
									'opsi'		=> $btn
									); 
			}
		}else{
			$hasil = [];
		}
		$this->tojson($hasil);
	}
	public function addUser(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->view('users/modalUser');
		}else{
			redirect(base_url());
		}
	}
	
	public function getUser(){
		$id		= $this->input->post('iduser');
		$data	= $this->admin_model->getData("users", "iduser = '$id'");
		$this->tojson($data);
	}
	
	public function checkUser(){
		$username =  $this->input->post('username');
		$data = $this->libcontrol->getuserdata($username);
		if($data->user_status == 1){
			$this->tojson($data->userdata);
		}
	}
	
	public function saveUser(){
		$state	= $this->input->post('state');
		//print_r($checkalias);exit;
		$iduke	= $this->input->post('uke'); 
		$getuke = json_decode(file_get_contents("https://bsdm.bappenas.go.id/app/service/api/unitkerja?id=".$iduke));
		//print_r($getuke);exit;
		$data	= array( 'username'	=> $this->input->post('username'),
						 'nama'		=> $this->input->post('nama'),
						 'iduke'	=> $getuke[0]->iduke,
						 'uke'		=> $getuke[0]->nama,
						 'role'		=> $this->input->post('role'),
						 'status'		=> "Active");
		if($state == 0){
			$db = $this->admin_model->addData($data, "users");
		}else{
			$id		= $this->input->post('iduser');
			$cond	= "iduser = '$id'";
			$db = $this->admin_model->updateData($data, $cond, "users");
		}
		$this->tojson($db);
	}
	public function delUser(){
		$id		  = $this->input->post('iduser');
		$cond	  = "iduser = '$id'";
		$data	  = array("status" => "Inactive");
		$db = $this->admin_model->updateData($data, $cond, "users");
		
		$this->tojson($db);
	}
	private function tojson($data){
		$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
	}
}
?>