<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

	/**
	 This script was created by Ahmad Bakhruddin Yusuf
	 ahmad.osoep@gmail.com
	 */
	
	public function __construct() {
        parent::__construct();
        $this->load->model("admin_model");
        $this->load->library("libcontrol");
        $this->load->library("template");
		$this->load->library('encode');
		date_default_timezone_set("Asia/Jakarta");
		
		if($this->session->userdata('islogin') != TRUE){
			redirect('admin/login');
		}
	}
	
	public function index(){
		$this->kegiatan();
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
	public function kegiatan(){
		$data = $this->templating();

		//$data['list_keg']	= $this->admin_model->getData("agenda");
		$data['content']  = $this->load->view('kegiatan/datatable', $data, TRUE);
      	$this->template->load('home', null, $data);
	}
	public function kegiatan_json(){
		if($this->session->userdata('role')== "1"){
			$kegiatan = $this->admin_model->getData("agenda", "status = 'Active'", "idagenda desc");
		}else{
			$iduke	= $this->session->userdata('iduke');
			$creator= $this->session->userdata('username');
			$kegiatan = $this->admin_model->getData("agenda", "status = 'Active' and creator = '$creator'", "idagenda desc");
		}
		if($kegiatan){
			$i=0;
			foreach ($kegiatan as $keg) {
				$i++; $idkegiatan = $keg['idagenda'];
				$idkeg_en = $this->encode->encoded($idkegiatan);
				$btn = '<a class="btn btn-success btn-xs" onclick=showmodalform('.$idkegiatan.')><i class="fa fa-bars"></i> Form</a><br><br>
						<a class="btn btn-primary btn-xs" href="'.base_url().'kegiatan/peserta/'.$idkeg_en.'"><i class="fa fa-users"></i> Peserta</a><br><br>
						<button id="Edit Kegiatan" type="button" class="btn btn-warning btn-xs" onclick=editkegiatan('.$idkegiatan.') data-toggle="modal" data-target="#modalKegiatan"><i class="fa fa-edit"></i> Edit</button>
						<a class="btn btn-danger btn-xs"  onclick=delkegiatan('.$idkegiatan.')><i class="fa fa-times-circle"></i> Hapus</a>';
				if($keg["alias"] != ""){
					$alias = base_url()."".$keg["alias"];
				}else{
					$alias = "";
				}
				
				if($this->session->userdata('role')== "1"){
					$hasil[] = array(	'no' 		=> $i,
										'kegiatan'	=> $keg['kegiatan'],
										'deskripsi' => $keg['deskripsi'],
										'start_date'=> $keg['start_date'],
										'end_date'	=> $keg['end_date'],
										'alias'		=> $alias,
										'creator'	=> $keg['creator'],
										'opsi'		=> $btn);
				}else{
					$hasil[] = array(	'no' 		=> $i,
										'kegiatan'	=> $keg['kegiatan'],
										'deskripsi' => $keg['deskripsi'],
										'start_date'=> $keg['start_date'],
										'end_date'	=> $keg['end_date'],
										'alias'		=> $alias,
										'opsi'		=> $btn); 
				}
			}
		}else{
			$hasil = [];
		}
		$this->tojson($hasil);
	}
	public function addKegiatan(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST'){
			$this->load->view('kegiatan/modalKegiatan');
		}else{
			redirect(base_url());
		}
	}
	public function getKegiatan(){
		$id		= $this->input->post('idagenda');
		$data	= $this->admin_model->getData("agenda", "idagenda = '$id'");
		$this->tojson($data);
	}
	public function saveKegiatan(){
		$state	= $this->input->post('state');
		$alias	= str_replace(' ', '_', $this->input->post('alias'));
		$checkalias = $this->admin_model->getData("agenda", "alias = '$alias' and status = 'Active'");
		//print_r($checkalias);exit;
		$data	= array( 'creator'	=> $this->session->userdata('username'),
						 'kegiatan'	=> $this->input->post('kegiatan'),
						 'deskripsi'=> $this->input->post('deskripsi'),
						 'date_create'	=> date('Y-m-d H:i:s'),
						 'start_date'	=> $this->input->post('start_date'),
						 'end_date'		=> $this->input->post('end_date'),
						 'alias'		=> $alias,
						 'uke_creator'	=> $this->session->userdata('iduke'),
						 'status'		=> "Active");
		$start	= date('Y-m-d H:i:s', strtotime($this->input->post('start_date')));
		$end	= date('Y-m-d H:i:s', strtotime($this->input->post('end_date')));
		
		if($start <= $end ){
			if($state == 0){
				if($checkalias == 0){
					$db = $this->admin_model->addData($data, "agenda");
				}else{
					$db = "duplicate";
				}
			}else{
				$idagenda = $this->input->post('idagenda');
				$cond	  = "idagenda = '$idagenda'";
				if($checkalias == 0){
					$db = $this->admin_model->updateData($data, $cond, "agenda");
				}elseif($checkalias[0]['idagenda'] == $idagenda){
					$db = $this->admin_model->updateData($data, $cond, "agenda");
				}else{
					$db = "duplicate";
				}
			}
		}else{
			$db = "tgl";
		}
		$this->tojson($db);
	}
	public function delKegiatan(){
		$idagenda = $this->input->post('idagenda');
		$cond	  = "idagenda = '$idagenda'";
		$data	  = array("status" => "Inactive");
		$db = $this->admin_model->updateData($data, $cond, "agenda");
		
		$this->tojson($db);
	}
	
	public function showPeserta($idkegiatan=""){
		if($idkegiatan!=""){
			$id		= $this->encode->decode($idkegiatan);
			$getdata= $this->admin_model->getData("agenda", "idagenda = '$id'");
			//print_r($id);exit;
			if($getdata){
				$data = $this->templating();
				
				$data['agenda']		= $getdata;
				$data['idkeg_en']	= $idkegiatan;
				$data['form']		= $this->admin_model->getData("form", "idagenda = '$id' and status = 'Active'");
				$data['content']	= $this->load->view('kegiatan/pesertatable', $data, TRUE);
				$this->template->load('home', null, $data);
			}else{
				echo "Upps";
				exit;
			}
		}else{
			echo "Upps";
			exit;
		}
	}
	
	public function peserta_json(){
		$idagenda= $this->input->post('idagenda');
		$peserta = $this->admin_model->getData("kehadiran", "idagenda = '$idagenda'", "idhadir desc");
		$form	 = $this->admin_model->getData("form", "idagenda = '$idagenda' and status = 'Active'", "idform asc");
		
		if($form){
			if($peserta){
				$i=0;
				foreach($peserta as $ps){
					$i++;
					$content_ = @unserialize($ps['custom']);
					if ($content_ !== false) {
						$content = $content_;
					} else {
						$content['no'] = $i;
						foreach($form as $fr){
							$content[$fr['nameform']] = "";
						}
						$content['sign'] = "";
					}
					$number  = array( 'no' => $i);
					$hasil[] = $number + $content;
				}
			}else{
				$hasil = [];
			}
		}else{
			if($peserta){
				$i=0;
				foreach ($peserta as $us) {
					$i++;
					$idhadir_en = $this->encode->encoded($us['idhadir']);
					$btn 		= '<a onclick="getsign('.$idhadir_en.')" class="btn btn-info btn-xs"><i class="fa fa-edit"></i>Show Sign</a>';
					
					$hasil[] = array(	'no' 		=> $i,
										'nama'		=> $us['nama'],
										'jabatan'	=> $us['jabatan'],
										'uke'		=> $us['uke'],
										'email'		=> $us['email'],
										'phone'		=> $us['phone'],
										'sign'		=> $us['sign'],
										'btn'		=> $btn); 
				}
			}else{
				$hasil = [];
			}
		}
		$this->tojson($hasil);
	}
	
	public function sign($idhadir_en=""){
		if($idhadir_en!=""){
			$idhadir = $this->encode->decode($idhadir_en);
			$getData = $this->admin_model->getData("kehadiran", "idhadir = '$idhadir'");
			
			if($getData){
				$img = $getData[0]['sign'];
				if($img == ""){
					$content = unserialize($getData[0]['custom']);
					$img_ = $content['sign'];
					echo "<img src='".$img_."' alt='Tanda Tangan User'/>";
				}else{
					echo "<img src='".$img."' alt='Tanda Tangan User'/>";
				}
			}
		}else{
			echo "Hayuu...";exit;
		}
	}
	public function exportpeserta($idkeg_en=""){
		if($idkeg_en){
			$idkeg	 = $this->encode->decode($idkeg_en);
			$getData = $this->admin_model->getData("kehadiran", "idagenda = '$idkeg'");
			$agenda  = $this->admin_model->getData("agenda", "idagenda = '$idkeg'");
			$form	 = $this->admin_model->getData("form", "idagenda = '$idkeg' and status = 'Active'", "idform asc");
			
			if($agenda){
				$nama_agenda = $agenda[0]['kegiatan'];
				if($getData){
					if($form){
						$i=0;
						foreach($getData as $ps){
							$i++;
							//$content = unserialize($ps['custom']);
							$content_ = @unserialize($ps['custom']);
							if ($content_ !== false) {
								$content = $content_;
							} else {
								foreach($form as $fr){
									$content[$fr['nameform']] = "";
								}
							}
							$sign 	 = base_url().'peserta/sign/'.$this->encode->encoded($ps['idhadir']);
							$number  = array( 'no' 	=> $i,
											  'sign'=> $sign);
							$hasil[] = $number + $content;	
						}
						$header = array('No');
						foreach($form as $fr){
							array_push($header, $fr['nama']);
						}
						array_push($header, "TTD");
					}else{
						$i=0;
						$header = array("No", "Nama", "Jabatan", "Instansi / Unit Kerja", "Email", "Telephone", "TTD");
						if($getData){
							foreach($getData as $ps){
								$i++;
								$sign = base_url().'peserta/sign/'.$this->encode->encoded($ps['idhadir']);
								$hasil[] = array(	'no' 	=> $i,
													'nama'	=> $ps['nama'],
													'jabatan'	=> $ps['jabatan'],
													'uke'	=> $ps['uke'],
													'email'	=> $ps['email'],
													'phone'	=> $ps['phone'],
													'sign'	=> $sign);
							}
						}else{
							$hasil[] = array(	'no' 	=> "--",
												'nama'	=> "--",
												'jabatan'	=> "--",
												'uke'	=> "--",
												'email'	=> "--",
												'phone'	=> "--",
												'sign'	=> "--");
						}
					}
					//print_r($header);exit;
					$this->load->library("excel");
					$object = new PHPExcel();
					$object->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					$object->setActiveSheetIndex(0);
					$column = 0;
					foreach($header as $field){
						$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
						$column++;
					}
					$excel_row = 2;
					if($form){
						foreach($hasil as $row){
							$x=1; $y=count($header)-1;
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['no']);
							foreach($form as $fm){
								if($fm['jenis'] == "number"){
									$row[$fm['nameform']] = "@".$row[$fm['nameform']];
									$object->getActiveSheet()->setCellValueByColumnAndRow($x, $excel_row, $row[$fm['nameform']]);
								}else{
									$object->getActiveSheet()->setCellValueByColumnAndRow($x, $excel_row, $row[$fm['nameform']]);
								}
								$x++;
							}
							$object->getActiveSheet()->setCellValueByColumnAndRow($y, $excel_row, $row['sign']);
							$excel_row++;
						}
					}else{
						foreach($hasil as $row){
							$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row['no']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['nama']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['jabatan']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['uke']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['email']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['phone']);
							$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['sign']);
								
							$excel_row++;
						}
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
					header('Content-Disposition: attachment;filename="Rekapitulasi Peserta '.$nama_agenda.'.xls"');
					$object_writer->save('php://output');
				}else{
					echo "Uppss";exit;
				}
			}
		}else{
			redirect(base_url());
		}
	}
	function topdf($idkeg_en=""){
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '300');
		ini_set("pcre.backtrack_limit", "900000000");
		if($idkeg_en){
			$idkeg	 = $this->encode->decode($idkeg_en);
			$data['kehadiran'] = $this->admin_model->getData("kehadiran", "idagenda = '$idkeg'");
			$data['kegiatan']  = $this->admin_model->getData("agenda", "idagenda = '$idkeg'");
			$data['form']	   = $this->admin_model->getData("form", "idagenda = '$idkeg' and status = 'Active'", "idform asc");
			
			if($data['kegiatan']){
				$mpdf = new \Mpdf\Mpdf();
				//$data['html']= $html;
				$nama_agenda = $data['kegiatan'][0]['kegiatan'];
				$pdfFilePath ="Rekapitulasi Peserta ".$nama_agenda.".pdf";
				$html = $this->load->view('kegiatan/peserta_cetak', $data, true);
				
				$css1 = file_get_contents('assets/css/bootstrap.min.css');
				$css2 = file_get_contents('assets/css/bootstrap-reset.css');
				$css3 = file_get_contents('assets/assets/font-awesome/css/font-awesome.css');
				$css4 = file_get_contents('assets/css/style.css');
				$css5 = file_get_contents('assets/css/style-responsive.css');
				$css6 = file_get_contents('assets/css/table-responsive.css');
				$css6 = file_get_contents('assets/datatables/jquery.dataTables.min.css');
				
				$mpdf->WriteHTML($css1, 1);
				$mpdf->WriteHTML($css2, 1);
				$mpdf->WriteHTML($css3, 1);
				$mpdf->WriteHTML($css4, 1);
				$mpdf->WriteHTML($css5, 1);
				$mpdf->WriteHTML($css6, 1);
				if($data['form']){
					if(count($data['form']) <= 7){
						$mpdf->AddPage('P','','','','',5,5,10,10,10,10);
					}else{
						$mpdf->AddPage('L','','','','',5,5,10,10,10,10);
					}
				}else{
					$mpdf->AddPage('P','','','','',5,5,10,10,10,10);
				}
				$mpdf->WriteHTML($html);
				//$mpdf->Output();
				$mpdf->Output($pdfFilePath, "I");
			}
		}
	}
	
	public function saveForm(){
		$state		= $this->input->post('state');
		$idagenda 	= $this->input->post('idagenda');
		
		$formx = $this->input->post('formx');
		$formy = $this->input->post('formy');
		$formz = $this->input->post('idform');
		$formzz= $this->input->post('formz');
		$state = $this->input->post('state');
		$kehadiran	= $this->admin_model->getData("kehadiran", "idagenda = '$idagenda'");
		
		if($kehadiran == false){
			if($formx!="" && $formy!=""){
				for($i=0; $i<count($formx); $i++){
					
					$data = array(	'idagenda' => $this->input->post('idagenda'),
									'nama'	   => $formx[$i],
									'nameform' => str_replace('.', '_', str_replace(' ', '', strtolower($formx[$i]))),
									'jenis'	   => $formy[$i],
									'required' => $formzz[$i],
									'status'   => "Active");
					
					if($state == 0){
						$db = $this->admin_model->addData($data, "form");
					}else{
						if(array_key_exists($i, $formz)){
							$idform = $formz[$i];
							$cond	= "idform = '$idform'";
							$db 	= $this->admin_model->updateData($data, $cond, "form");
						}else{
							$db = $this->admin_model->addData($data, "form");
						}
					}
				}
			}
		}else{
			$db = false;
		}
		$this->tojson($db);
	}
	
	public function getForm(){
		$id 	= $this->input->post('idagenda');
		$data	= $this->admin_model->getData("form", "idagenda = '$id' and status = 'Active'", "idform desc");
		$this->tojson($data);
	}
	public function delForm(){
		$idform	  = $this->input->post('idform');
		$cond	  = "idform = '$idform'";
		$checkdata= $this->admin_model->check_kehadiran_byidform($idform);
		if($checkdata == false){
			$data	  = array("status" => "Inactive");
			$db = $this->admin_model->updateData($data, $cond, "form");
		}else{
			$db = false;
		}
		$this->tojson($db);
	}
	private function tojson($data){
		$this->output
				->set_content_type('application/json')
				->set_output(json_encode($data));
	}
}
?>