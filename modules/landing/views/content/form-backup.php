	<section id="" class="padd-section text-center" style="background-color: #e5f4e1;">
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
				<div class="col-xl-8 col-lg-8 col-md-9 col-11 text-center" style="background-color: #ffffff;padding: 10px 10px 10px 10px;">
					<div id="formcontainer" class="card" style="background-color: #ffffff;padding: 10px 10px 10px 10px;">
						<h4><?=$rapat[0]['kegiatan']?></h4>
						<hr>
						<form id="formsign" class="form-card" onsubmit="event.preventDefault()">
							<input type="hidden" name="agenda" id="agenda" value="<?=$idagenda?>">
							<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-label">Nama</label>
									<input type="text" class="form-control" id="nama" name="nama" placeholder="Enter your name" onblur="validate(1)">
								</div>
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-label">Email</label>
									<input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" onblur="validate(2)">
								</div>
							</div>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-label">No. Handphone</label>
									<input type="number" class="form-control" id="nohp" name="nohp" placeholder="Enter your phone number" onblur="validate(3)">
								</div>
								<div class="form-group col-sm-6 flex-column d-flex">
									<label class="form-label">Jabatan</label>
									<input type="text" class="form-control" id="jabatan" name="jabatan" placeholder=" Enter your employment" onblur="validate(4)">
								</div>
							</div>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-sm-12 flex-column d-flex">
									<label class="form-label">Instansi / Unit Kerja</label>
									<input type="text" class="form-control" id="uke" name="uke" placeholder="Enter your workunit" onblur="validate(3)">
								</div>
							</div>
							<div class="row justify-content-between text-left mb-3">
								<div class="form-group col-12 flex-column d-flex">
									<label class="form-label">Tanda Tangan</label>
									<div class="row">
										<div class="col-md-12">
											<canvas id="signature-pad" width="900" height="200"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="row justify-content-end mb-3">
								<div class="form-group col-sm-12 text-center">
									<button class="btn-sm btn-secondary" id="clear">Hapus Tanda Tangan</button>
									<button class="btn-sm btn-primary" id="send">Hadir</button>
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
			<div class="section-title text-center">
				<h2>Kegiatan Belum Dimulai</h2>
				<h4><?=$rapat[0]['kegiatan']?>, <?=$rapat[0]['start_date']?></h4>
				<h4><?=$rapat[0]['deskripsi']?></h4>
			</div>
		</div>
		<div class="container" id="judul2" data-aos="fade-up" style="margin-top: 2em;">
			<div class="section-title text-center">
				<h2>Kegiatan Belum Dimulai</h2>
				<h4><?=$rapat[0]['kegiatan']?>, <?=$rapat[0]['start_date']?></h4>
				<h4><?=$rapat[0]['deskripsi']?></h4>
			</div>
		</div>
		<?php
		}else{
		?>
		<div class="container" id="judul1" data-aos="fade-up" style="margin-top: 5%;">
			<div class="section-title text-center">
				<h2>Kegiatan Telah Berakhir</h2>
				<h4><?=$rapat[0]['kegiatan']?>, <?=$rapat[0]['start_date']?> s/d <?=$rapat[0]['end_date']?></h4>
				<h4><?=$rapat[0]['deskripsi']?></h4>
			</div>
		</div>
		<div class="container" id="judul2" data-aos="fade-up" style="margin-top: 2em;">
			<div class="section-title text-center">
				<h2>Kegiatan Telah Berakhir</h2>
				<h4><?=$rapat[0]['kegiatan']?>, <?=$rapat[0]['start_date']?> s/d <?=$rapat[0]['end_date']?></h4>
				<h4><?=$rapat[0]['deskripsi']?></h4>
			</div>
		</div>
		<?php
		}
		?>
    </section><!-- End Get Started Section -->
	<script>
	var canvas = document.getElementById('signature-pad');

	var signaturePad = new SignaturePad(canvas, {
	  backgroundColor: 'rgb(255, 255, 255)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
	});


	document.getElementById('clear').addEventListener('click', function () {
		//console.log(signaturePad.toDataURL());
		signaturePad.clear();
	});
	
	document.getElementById('send').addEventListener('click', function () {
		var dataForm = new FormData(formsign);
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
				  text: "Terimakasih Telah Berpartisipasi Dalam Acara Ini.",
				  icon: "success",
			  }).then((value) => {
				$('#formcontainer').html("<span>Terimakasih Atas Partisipasi Anda</span>");
			  }); 
			}else{
			  swal("Error", "Terjadi Kesalahan, Mohon Coba Lagi", "error");
			  $('#send').prop("disabled", false);
			  $('#send').text('Simpan');
			}
		  }
		});
	});
	
	/*$('#formsign').submit(function(e){
		e.preventDefault();
		
	});
	*/
	</script>