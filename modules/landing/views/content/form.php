	<style>
	label{
		text-align : left;
		font-weight: 500;
	}
	.text-secondary{
		text-align: left !important;
	}
	.mb-0{
		text-align: left;
	}
	.select2-selection__rendered{
		text-align: left;
	}
	</style>
	<section id="" class="padd-section text-center" style="background-color: #e5f4e1;height:100vh;overflow:auto;">
		<?php
		$start	= date('Y-m-d H:i:s', strtotime($rapat[0]['start_date']));
		$end	= date('Y-m-d H:i:s', strtotime($rapat[0]['end_date']));
		$now	= date('Y-m-d H:i:s', strtotime($date));
		
		if($now > $start && $now <= $end){
		?>
		<div class="container" id="judul1" data-aos="fade-up" style="margin-top: 2%;">
			<div class="section-title text-center">
				<h2>Form Kehadiran <br></h2>
			</div>
		</div>
		<div class="container" id="judul2" data-aos="fade-up" style="margin-top: 2em;">
			<div class="section-title text-center">
				<h3>Form Kehadiran <br></h3>
			</div>
		</div>
		
		<div class="container-fluid px-1 mx-auto">
			<div class="row d-flex justify-content-center">
				<div class="col-xl-5 col-lg-5 col-md-9 col-7 text-center" style="background-color: #ffffff;padding: 10px 10px 10px 10px;">
					<div id="formcontainer" class="card" style="background-color: #ffffff;padding: 10px 10px 10px 10px;">
						<h4><?=$rapat[0]['kegiatan']?></h4>
						<hr>
						<form id="formsign" class="form-card" onsubmit="event.preventDefault()">
							<input type="hidden" name="agenda" id="agenda" value="<?=$idagenda?>">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							<?php
							if($form){
								foreach($form as $fr){
									if($fr['jenis'] == "number"){
										$min = 'min="0"';
									}else{
										$min = "";
									}
							?>
							<input type="hidden" name="custom" id="custom" value="true">
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label"><?=$fr['nama']?></label>
									<?php
									if($fr['required'] == "required"){
										$required = "required";
									}else{
										$required = "";
									}
									
									if($fr['jenis'] == "custuke"){
									?>
									<select class="form-control select2" id="<?=$fr['nameform']?>" name="<?=$fr['nameform']?>" <?=$required?>>
										<option value="" disabled selected>Pilih Unitkerja</option>
									<?php
										foreach($uke as $uk){
									?>	
										<option value="<?=$uk->nama?>"><?=$uk->nama?></option>
									<?php
										}
									?>
									</select>
									<?php
									}elseif($fr['jenis'] == "kelamin"){
									?>
									<select class="form-control select2" id="<?=$fr['nameform']?>" name="<?=$fr['nameform']?>" <?=$required?>>
										<option value="" disabled selected>Pilih Jenis Kelamin</option>
										<option value="Laki-Laki">Laki-Laki</option>
										<option value="Perempuan">Perempuan</option>
									</select>
									<?php
									}elseif($fr['jenis'] == "statpeg"){
									?>
									<select class="form-control select2" id="<?=$fr['nameform']?>" name="<?=$fr['nameform']?>" <?=$required?>>
										<option value="" disabled selected>Pilih Status Pegawai</option>
										<option value="PNS">PNS</option>
										<option value="PPNPN">PPNPN</option>
									</select>
									<?php
									}else{
									?>
									<input type="<?=$fr['jenis']?>" <?=$min?> class="form-control" id="<?=$fr['nameform']?>" name="<?=$fr['nameform']?>" placeholder="Isi data Anda" <?=$required?>/>
									<?php
									}
									?>
								</div>
							</div>
							<?php
								}
							}else{
							?>
							<input type="hidden" name="custom" id="custom" value="false">
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">Nama</label>
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Enter your name" onblur="validate(1)" required>
								</div>
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">Email</label>
									<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" onblur="validate(2)">
								</div>
							</div>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">No. Handphone</label>
									<input type="number" class="form-control" id="nohp" name="nohp" placeholder="Enter your phone number" onblur="validate(3)">
								</div>
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">Jabatan</label>
									<input type="text" class="form-control" id="jabatan" name="jabatan" placeholder=" Enter your employment" onblur="validate(4)">
								</div>
							</div>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">Instansi / Unit Kerja</label>
									<input type="text" class="form-control" id="uke" name="uke" placeholder="Enter your workunit" onblur="validate(3)" required>
								</div>
							</div>
							<?php							
							}
							?>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-12 flex-column d-flex">
									<label class="form-label">Tanda Tangan</label>
									<div class="row">
										<div class="col-md-12">
											<canvas id="signature-pad" width="600" height="200"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-end mb-3">
								<div class="form-group col-sm-12 text-center">
									<button class="btn-sm btn-secondary" id="clear">Hapus Tanda Tangan</button>
									<button type="submit" class="btn-sm btn-primary" id="send">Hadir</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php 
		}elseif($now < $start){
		?>
		<div class="container" id="judul1" data-aos="fade-up" style="margin-top: 5%;">
			<div class="alert alert-light" role="alert">
				<h4 class="alert-heading">Kegiatan Belum Dimulai</h4>
				<hr>
				<div class="col-lg-12 mb-3 mx-auto">
					<div class="card mt-3 mx-auto" style="border: none;">
						<ul class="list-group list-group-flush" style="border: none;">
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Kegiatan</h6>
								<span class="text-secondary col-md-8"><?=$rapat[0]['kegiatan']?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Waktu</h6>
								<span class="text-secondary col-md-8"><?=format_hari($rapat[0]['start_date']);?>, <?=format_tgl($rapat[0]['start_date'])?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Deskripsi</h6>
								<span class="text-secondary text-end col-md-8"><?=$rapat[0]['deskripsi']?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="judul2" data-aos="fade-up" style="margin-top: 5%;">
			<div class="alert alert-light" role="alert">
				<h4 class="alert-heading">Kegiatan Belum Dimulai</h4>
				<hr>
				<div class="col-lg-12 mb-3 mx-auto">
					<div class="card mt-3 mx-auto" style="border: none;">
						<ul class="list-group list-group-flush" style="border: none;">
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Kegiatan</h6>
								<span class="text-secondary"><?=$rapat[0]['kegiatan']?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Waktu</h6>
								<span class="text-secondary"><?=format_hari($rapat[0]['start_date']);?>, <?=format_tgl($rapat[0]['start_date'])?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Deskripsi</h6>
								<span class="text-secondary text-end"><?=$rapat[0]['deskripsi']?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
		}else{
		?>
		<div class="container" id="judul1" data-aos="fade-up" style="margin-top: 5%;">
			<div class="alert alert-light" role="alert">
				<h4 class="alert-heading">Kegiatan Telah Berakhir</h4>
				<hr>
				<div class="col-lg-12 mb-3 mx-auto">
					<div class="card mt-3 mx-auto" style="border: none;">
						<ul class="list-group list-group-flush" style="border: none;">
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Kegiatan</h6>
								<span class="text-secondary col-md-8"><?=$rapat[0]['kegiatan']?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Waktu</h6>
								<span class="text-secondary col-md-8"><?=format_hari($rapat[0]['start_date']);?>, <?=format_tgl($rapat[0]['start_date'])?> s/d <?=format_tgl($rapat[0]['end_date'])?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0 col-md-2">Deskripsi</h6>
								<span class="text-secondary text-end col-md-8"><?=$rapat[0]['deskripsi']?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="container" id="judul2" data-aos="fade-up" style="margin-top: 5%;">
			<div class="alert alert-light" role="alert">
				<h4 class="alert-heading">Kegiatan Telah Berakhir</h4>
				<hr>
				<div class="col-lg-12 mb-3 mx-auto">
					<div class="card mt-3 mx-auto" style="border: none;">
						<ul class="list-group list-group-flush" style="border: none;">
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Kegiatan</h6>
								<span class="text-secondary"><?=$rapat[0]['kegiatan']?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Waktu</h6>
								<span class="text-secondary"><?=format_hari($rapat[0]['start_date']);?>, <?=format_tgl($rapat[0]['start_date'])?> s/d <?=format_tgl($rapat[0]['end_date'])?></span>
							</li>
							<li class="list-group-item d-flex align-items-center flex-wrap" style="border: none;">
								<h6 class="mb-0">Deskripsi</h6>
								<span class="text-secondary text-end"><?=$rapat[0]['deskripsi']?></span>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		?>
    </section><!-- End Get Started Section -->
	<script>
	var canvas = document.getElementById('signature-pad');
	fitToContainer(canvas);
	var signaturePad = new SignaturePad(canvas, {
	  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
	});
	
	function fitToContainer(canvas){
	  canvas.style.width='100%';
	  canvas.style.height='100%';
	  canvas.width  = canvas.offsetWidth;
	  canvas.height = canvas.offsetHeight;
	}

	$(function() {
		$(".select2").select2();
		
		document.getElementById('clear').addEventListener('click', function (e) {
			//console.log(signaturePad.toDataURL());
			e.preventDefault();
			signaturePad.clear();
		});
		

		$('#formsign').submit(function(e){
			e.preventDefault();
			
			var dataForm = new FormData(formsign);
			
			if(signaturePad.isEmpty()){
				swal("Error", "Anda harus mengisi tanda tangan terlebih dahulu", "error");
					  $('#send').prop("disabled", false);
					  $('#send').text('Simpan');
			}else{
				dataForm.append('signdata', signaturePad.toDataURL());
				//console.log(dataForm);
				
				$.ajax({
				  url: "<?=base_url('landing/data/sendData')?>",
				  type: 'POST',
				  data: dataForm,
				  //async: false,
				  cache: false,
				  contentType: false,
				  processData: false,
				  beforeSend: function() { 
					$('#send').attr('disabled','disabled');
					$('#send').text('Loading ...');
				  },
				  success: function (data) {
					if (data) {
						
					  swal({
						  title: "Berhasil",
						  text: "Terima kasih telah berpartisipasi dalam acara ini.",
						  icon: "success",
					  }).then((value) => {
						$('#formcontainer').html("<span>Terima kasih atas partisipasi Anda</span>");
					  }); 
					}else{
					  swal("Error", "Terjadi kesalahan, mohon coba lagi", "error");
					  $('#send').prop("disabled", false);
					  $('#send').text('Simpan');
					}
				  }
				});
			}
		});
	});
	</script>