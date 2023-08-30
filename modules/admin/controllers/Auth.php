<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	/**
	 This script was created by Ahmad Bakhruddin Yusuf
	 ahmad.osoep@gmail.com
	 */
	
	public function __construct() {
        parent::__construct();
        $this->load->model("admin_model");
        $this->load->library("libcontrol");
	}
	
	function index(){
		$data = $this->libcontrol->getData();
		if(empty($data->data)){
			$this->logout();
			exit();
		}else{
			if ($data->kunci_status=="Success") {
				if($data->userdata){
					$this->authprocess($data->userdata);
				}else{
					$this->logout();
				}
			}else{
				$this->logout();
			}
		}
	}
	
	private function authprocess($data){
		$username = $data[0]->user_name;
		$check = $this->admin_model->getData("users", "username='$username'"); 
		if($check) {
		    $userdata = array(  "islogin"	=> TRUE,
								"username"	=> $check[0]['username'],
								"name"		=> $check[0]['nama'],
								"jabatan"	=> $data[0]->jabatan_akhir,
								"iduke"		=> $check[0]['iduke'],
								"uke"		=> $check[0]['uke'],
								"role"		=> $check[0]['role']);
		
		}else{
			if($data[0]->id_uke_bsdm){
				$url = "https://bsdm.bappenas.go.id/app/service/api/unitkerja?id=".substr($data[0]->id_uke_bsdm, 0, 4);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_URL, $url);
				$result = json_decode(curl_exec($ch));
				$http_code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				
				if($http_code == "200"){
					$iduke  = $result[0]->iduke;
					$uke	= $result[0]->nama;
				}else{
					$iduke  = "";
					$uke	= "";
				}
			}else{
				$iduke  = "";
				$uke	= "";
			}
			$userdata = array(  "islogin"	=> TRUE,
								"username"	=> $data[0]->user_name,
								"name"		=> $data[0]->nama,
								"jabatan"	=> $data[0]->jabatan_akhir,
								"iduke"		=> $iduke,
								"uke"		=> $uke,
								"role"		=> "2");
		}
		$this->session->set_userdata($userdata);
		redirect(base_url());
	}
	
	function logout(){
		$this->libcontrol->deleteSession();
		$this->session->sess_destroy();
		header("location: https://akun.bappenas.go.id/bp-um/service/front/daftarhadir/F8iPcsqVlqPlOdTjIH4xGhWUNVwK2c5CKXht5EuWezJbyCZNEpHHI3ChSenNxhgPaitS4YXwlTtTRnxtHi%21Nyg==");
	}
}