<style>
table.dataTable tbody td {
    word-break: break-word;
    line-height: 1.5em;
    height: 3em;
    overflow: hidden;
}
</style>	
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   <b>Daftar Kegiatan</b>
                </header>
                <div class="panel-body">
                    <section id="unseen">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-condensed" id="tableagenda">
								<thead>
								<tr>
									<th>No. </th>
									<th>Kegiatan</th>
									<th>Deskripsi</th>
								<?php
									if($this->session->userdata('role')== "1"){
								?>
									<th>Creator</th>
								<?php
									}
								?>	
									<th>URL Alias</th>
									<th>Mulai</th>
									<th>Selesai</th>
									<th>Action</th>
								</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
                    </section>
                </div>
            </section>
			<div id="formmodal">
			<style>
			.form-control{
				color: #212121;
			}
			::placeholder{
				opacity : 0.5;
			}
			</style>
				<div class="modal fade" id="modalKegiatan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title modal_kegiatan">Tambah Kegiatan</h4>
							</div>
							<div class="modal-body">
								<form id="form_kegiatan" >
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									<input type="hidden" class="form-control" id="state" name="state">
									<input type="hidden" class="form-control" id="idagenda" name="idagenda">
									<div class="form-group">
										<label for="exampleInputEmail1">Judul</label>
										<input type="text" class="form-control" id="kegiatan" name="kegiatan" placeholder="Masukkan Judul Kegiatan" required>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Alias ( <?=base_url()?>{Alias Kegiatan} )</label>
										<input type="text" class="form-control" id="alias" name="alias" placeholder="Masukkan Alias Kegiatan Untuk Pemendekan URL" required>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Deskripsi</label>
										<textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan Deskripsi Kegiatan" required></textarea>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Mulai</label>
										<input type="text" class="form-control" id="start_date" name="start_date" placeholder="Mulai Kegiatan" autocomplete="off" required>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Selesai</label>
										<input type="text" class="form-control" id="end_date" name="end_date" placeholder="Selesai Kegiatan" autocomplete="off" required>
									</div>
									<div class="modal-footer">
										<button data-dismiss="modal" class="btn btn-default" type="button">Keluar</button>
										<button class="btn btn-success" id="send" type="submit">Simpan</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Custom Form Kegiatan</h4>
							</div>
							<div class="modal-body">
								<form id="form_data" >
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									<input type="hidden" class="form-control" id="statex" name="state">
									<input type="hidden" class="form-control" id="idagendax" name="idagenda">
									
									<div class="form-group" id="masterform">
										<header class="panel-heading">Tambah Custom Form Kegiatan <button class="btn btn-primary pull-right" id="addForm"><i class="fa fa-plus"></i> Add</button></header>
									</div>
									<div class="modal-footer" id="masterform2">
										<button data-dismiss="modal" class="btn btn-default" type="button">Keluar</button>
										<button class="btn btn-success" id="send" type="submit">Simpan</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				
			</div>
        </div>
    </div>
    <!-- page end-->
</section>

<script type="text/javascript">
	$(document).ready( function () {
		var count1 = 0;
		
		$.fn.dataTable.ext.buttons.addData = {
			text: '<i class="fa fa-plus"></i> Tambah Kegiatan',
			action: function ( e, dt, node, config ) {
				showModal();
			}
		};
		var button= [ { extend: 'addData', className: 'btn-primary btn-sm' }];
		$('#tableagenda').DataTable({
			bFilter: true, 
			dom: 'Bfrtilp',
			//scrollX: true,
			sAjaxDataProp:"",
			ajax: {
				"url": "<?=base_url('admin/data/kegiatan_json')?>",
				"type": "POST",
				"data" : {<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			},
			columns: [
					{"title": "No", "data": "no"},
					{"title": "Kegiatan","data":"kegiatan", width : '17%'},
					{"title": "Deskripsi" , "data":"deskripsi", width : '25%'},
					<?php
					if($this->session->userdata('role')== "1"){
					?>
					{"title": "Creator" , "data":"creator"},
					<?php	
					}
					?>
					{"title": "Url Alias", "data" : "alias", width : '20%',
							"render": function(data, type, row, meta){
							  if(type === 'display'){
								  data = '<a href="' + row.alias + '" target="_blank">' + data + '</a>';
							  }
							  return data;
							}},
					{"title": "Mulai" , "data":"start_date"},
					{"title": "Selesai" , "data":"end_date"},
					{"title": "Action" , "data":"opsi"}
				],
				"columnDefs": [
					{ "max-width": "20%", "targets": 2 },
				],
			order: [[0, 'asc']],
			lengthMenu: [[ 10, 25, 50, 100, 1000 ],
						 [ '10', '25', '50', '100', 'All' ]
						],
			buttons: button
		});
		
		$('.dt-button').css({"padding":"10px", "position":"absolute"});
		$('.dataTables_filter').css({"width": "35%"});
		
		//for submit modal
		$("#start_date").datetimepicker({
				format : 'yyyy-mm-dd hh:ii',
				startDate : '<?php echo date("Y-m-d H:i")?>',
				autoclose: true
		});

		$("#start_date").change(function() {
			$("#end_date").val("");
			$("#end_date").datetimepicker({
				format: 'yyyy-mm-dd hh:ii',
				startDate: '<?php echo date("Y-m-d H:i")?>', //$("#start_date").val(),
				autoclose: true
			});
		});
		
		$("#addForm").click(function(e){
			count1+=1;
			e.preventDefault();
			$("#masterform2").before('<div class="form-group formclass" id="'+count1+'"><label for="exampleInputEmail1">&nbsp;&nbsp;</label><div class="col-md-5"><input type="text" class="form-control" name="formx[]" placeholder="Masukkan Nama Kolom" required/></div><div class="col-md-4"><select class="form-control" name="formy[]" required><option value="" disabled selected>Pilih Jenis Kolom</option><option value="text">Text & Number</option><option value="number">Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option value="statpeg">Status Pegawai</option></select></div><div class="col-md-2"><select class="form-control" name="formz[]"><option value="">None</option><option value="required">Required</option></select></div><button class="btn btn-danger btn-sm" id="delform"><i class="fa fa-minus"></i></button></div>');
		})
		
		 $('#form_data').on('click', '#delform', function (e) {
			e.preventDefault();
			var parentid = $(this).parent().attr('id');
			if($(this).val()){
				$.ajax({
					url: "<?=base_url('admin/data/delForm')?>",
					type: 'POST',
					data: {idform:$(this).val(),<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
					//async: false,
				 
					success: function (data) {
						if(data == 1){
							//console.log(parentid);
							$("#"+parentid).remove();
						}else{
							swal("Error", "Telah Dilakukan Pengisian Data Oleh User, Pengurangan Form Gagal", "error");
						}
						
					},
					/*cache: false,
					contentType: false,
					processData: false*/
				}); 
			}else{
				$(this).parent().remove();
			}
		});
		
		$('#form_kegiatan').submit(function(e){
			e.preventDefault();

			$.ajax({
			  url: "<?=base_url('admin/data/saveKegiatan')?>",
			  type: 'POST',
			  data: $(this).serialize(),
			  //async: false,
			  beforeSend: function() { 
				$('#send').attr('disabled','disabled');
				$('#send').text('Loading ...');
			  },
			  success: function (data) {
				//console.log(data);
				if (data == "duplicate") {
					swal("Error", "Alias Sudah Terdaftar, Silahkan Menggunakan Alias Lain", "error");
					  $('#send').prop("disabled", false);
					  $('#send').text('Simpan');
				}else if(data == "tgl"){
					swal("Error", "Kesalahan Pemilihan Tanggal", "error");
					  $('#send').prop("disabled", false);
					  $('#send').text('Simpan');
				}else if(data == 1){
				  $('#modalKegiatan').modal('hide');
				  swal({
					  title: "Berhasil",
					  text: "Berhasil menambahkan data",
					  icon: "success",
					  timer: 2000,
    				  buttons: false
				  }).then((value) => {
					drawtable();
				  }); 
				}else{
				  swal("Error", "Tambah data gagal, Mohon Coba Lagi", "error");
				  $('#send').prop("disabled", false);
				  $('#send').text('Simpan');
				}
			  },
			  /*cache: false,
			  contentType: false,
			  processData: false*/
			}); 
		});
		
		$('#form_data').submit(function(e){
			e.preventDefault();
			
			$.ajax({
			  url: "<?=base_url('admin/data/saveForm')?>",
			  type: 'POST',
			  data: $(this).serialize(),
			  //async: false,
			  beforeSend: function() { 
				$('#send').attr('disabled','disabled');
				$('#send').text('Loading ...');
			  },
			  success: function (data) {
				//console.log(data);
				if (data) {
					$("#modalForm").modal("hide");
					swal({
						title: "Berhasil",
						text: "Berhasil mengubah data.",
						icon: "success",
						timer: 2000,
    					buttons: false
					}).then((value) => {
						drawtable();
					});
				}else{
				  swal("Error", "Telah Dilakukan Pengisian Data Oleh User, Perubahan Form Gagal", "error");
				  $('#sendx').prop("disabled", false);
				  $('#sendx').text('Simpan');
				}
			  },
			  /*cache: false,
			  contentType: false,
			  processData: false*/
			}); 
		})
	});
	
	function drawtable() {
		$('#tableagenda').DataTable().ajax.reload();
	}

	function showModal() {
		$('.modal_kegiatan').text("Tambah Kegiatan");
		$("#state").val(0);
		$("#idagenda").val("");
		$("#kegiatan").val("");
		$("#alias").val("");
		$("#deskripsi").val("");
		$("#start_date").val("");
		$("#end_date").val("");
		
		$('#modalKegiatan').modal({backdrop: 'static', keyboard: false});
		$('#send').prop("disabled", false);
		$('#send').text('Simpan');
		$("#modalKegiatan").modal("show");
	}

	function delkegiatan(id) {
		//alert(id);
		swal({
		  title: "Apakah Anda Yakin ?",
		  text: "Klik hapus untuk menghapus kegiatan",
		  icon: "warning",
		  buttons: ["Batal", "Hapus"],
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			$.ajax({
				data  : {idagenda:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
				url: "<?=base_url('admin/data/delKegiatan')?>",
				method: "POST",
				dataType: 'json',
				success: function(data) {
				if(data){
					swal({
						title: "Success",
						text: "Berhasil menghapus data",
						icon: "success",
						timer: 2000,
    					buttons: false
					}).then((value) => {
						drawtable();
					});
				}
			  },
			  error: function (xhr, ajaxOptions, thrownError) {
				  console.log(xhr.status);
				  console.log(thrownError);
			  }
			});
		  }
		});
	}

	function editkegiatan(id) {
		$('#modalKegiatan').on('shown.bs.modal', function(e){
			const buttonId = e.relatedTarget.id;
			$(this).find('.modal-title').text(`${buttonId}`);      
		});

		$.ajax({
			data  : {idagenda:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			url: "<?=base_url('admin/data/getKegiatan')?>",
			method: "POST",
			dataType: 'HTML',
			success: function(data) {
				const dat = JSON.parse(data);
				$("#state").val(1);
				$("#idagenda").val(dat[0].idagenda);
				$("#kegiatan").val(dat[0].kegiatan);
				$("#alias").val(dat[0].alias);
				$("#deskripsi").val(dat[0].deskripsi);
				$("#start_date").val(dat[0].start_date);
				$("#end_date").val(dat[0].end_date);
				/*
				if($("#start_date").val()){
					$("#end_date").datetimepicker({
						format: 'yyyy-mm-dd hh:ii',
						startDate: $("#start_date").val(),
						autoclose: true
					});
				}*/
				
				$('#send').prop("disabled", false);
				$('#send').text('Simpan');
				$('#modalKegiatan').modal({backdrop: 'static', keyboard: false});
				$("#modalKegiatan").modal("show");
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	}
	
	function showmodalform(id){
		$("#idagendax").val(id);
		var count1 = 0;
		
		$.ajax({
			data  : {idagenda:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			url: "<?=base_url('admin/data/getForm')?>",
			method: "POST",
			dataType: 'HTML',
			success: function(data) {
				$(".formclass").remove();
				
				if(data == "0"){
					$('#statex').val('0');
				}else{
					$('#statex').val('1');
					const dat = JSON.parse(data);
					for(var x in dat){
						count1+=1;
						
						if(dat[x].jenis == "text"){
							options = '<option value="" disabled>Pilih Jenis Form</option><option value="text" selected>Text & Number</option><option value="number">Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option value="statpeg">Status Pegawai</option>';
						}else if(dat[x].jenis == "number"){
							options = '<option value="" disabled>Pilih Jenis Form</option><option value="text">Text & Number</option><option value="number" selected>Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option values="statpeg">Status Pegawai</option>';
						}else if(dat[x].jenis == "custuke"){
							options = '<option value="" disabled>Pilih Jenis Form</option><option value="text">Text & Number</option><option value="number">Number Only</option><option value="custuke" selected>Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option value="statpeg">Status Pegawai</option>';
						}else if(dat[x].jenis == "kelamin"){
							options = '<option value="" disabled>Pilih Jenis Form</option><option value="text">Text & Number</option><option value="number">Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin" selected>Jenis Kelamin</option><option value="statpeg">Status Pegawai</option>';
						}else if(dat[x].jenis == "statpeg"){
							options = '<option value="" disabled>Pilih Jenis Form</option><option value="text">Text & Number</option><option value="number">Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option value="statpeg" selected>Status Pegawai</option>';
						}else{
							options = '<option value="" disabled selected>Pilih Jenis Form</option><option value="text">Text & Number</option><option value="number">Number Only</option><option value="custuke">Daftar Unit Kerja Bappenas </option><option value="kelamin">Jenis Kelamin</option><option value="statpeg">Status Pegawai</option>';
						}
						
						if(dat[x].required == "required"){
							option2 = '<option value="">None</option><option value="required" selected>Required</option>';
						}else{
							option2 = '<option value="" selected>None</option><option value="required">Required</option>';
						}
						//console.log(options);
						$("#masterform").after('<div class="form-group formclass" id="'+count1+'"><label for="exampleInputEmail1">&nbsp;&nbsp;</label><input type="hidden" name="idform[]" value="'+dat[x].idform+'"><div class="col-md-5"><input type="text" class="form-control" value="'+dat[x].nama+'" name="formx[]" placeholder="Masukkan Nama Form" required/></div><div class="col-md-4"><select class="form-control" name="formy[]" required>'+options+'</select></div><div class="col-md-2"><select class="form-control" name="formz[]">'+option2+'</select></div><button class="btn btn-danger btn-sm" id="delform" value="'+dat[x].idform+'"><i class="fa fa-minus"></i></button></div>');
					}
				}
				
				$('#sendx').prop("disabled", false);
				$('#sendx').text('Simpan');
				$('#modalForm').modal({backdrop: 'static', keyboard: false});
				$("#modalForm").modal("show");
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	
	}
</script>      