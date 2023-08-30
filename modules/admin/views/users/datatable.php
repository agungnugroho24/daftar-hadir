<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   <b>Daftar Users</b>
                </header>
                <div class="panel-body">
                    <section id="unseen">
						<div class="table-responsive">
							<table class="table table-bordered table-striped table-condensed" id="tableusers">
								<thead>
								<tr>
									<th>No. </th>
									<th>Nama</th>
									<th>Unit kerja</th>
									<th>Role</th>
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
				<div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title">Add User</h4>
							</div>
							<div class="modal-body">
								<form id="form_user" >
									<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
									<input type="hidden" class="form-control" id="state" name="state">
									<input type="hidden" class="form-control" id="iduser" name="iduser">
									<div class="form-group">
										<label for="exampleInputEmail1">Username</label>
										<input type="text" class="form-control" id="username" name="username" placeholder="Masukkan Username">
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Nama</label>
										<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Pengguna" autocomplete="off" required>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Unit kerja</label>
										<select class="form-control m-bot15" id="uke" name="uke" required>
											<option value="">---Pilih---</option>
											<?php foreach($list_uke as $uke){?>
											<option value="<?=$uke->iduke?>"><?=$uke->nama?></option>
											<?php }?>
										</select>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Role</label>
										<select class="form-control m-bot15" id="role" name="role" required>
											<option value="">---Pilih---</option>
											<option value="1">Administrator</option>
											<!--<option value="2">Admin UKE</option>-->
										</select>
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
			</div>
        </div>
    </div>
    <!-- page end-->
</section>

<script type="text/javascript">


	$(document).ready( function () {

		$.fn.dataTable.ext.buttons.addData = {
			text: '<i class="fa fa-plus"></i> Add User',
			action: function ( e, dt, node, config ) {
				showModal();
			}
		};
		var button= [ { extend: 'addData', className: 'btn-primary btn-sm' }];
		$('#tableusers').DataTable({
			bFilter: true, 
			dom: 'Bfrtilp',
			sAjaxDataProp:"",
			ajax: {
				"url": "<?=base_url('admin/user/users_json')?>",
				"type": "POST",
				"data" : {<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			},
			columns: [
					{"title": "No", "data": "no"},
					{"title": "Nama","data":"nama"},
					{"title": "Unit kerja" , "data":"uke"},
					{"title": "Role" , "data":"role"},
					{"title": "Action" , "data":"opsi"}
				],
				"columnDefs": [
					{ "width": "5%", "targets": 0 }
				],
			order: [[0, 'asc']],
			lengthMenu: [[ 10, 25, 50, 100, 1000 ],
						 [ '10', '25', '50', '100', 'All' ]
						],
			buttons: button
		});
		$('.dt-button').css({"padding":"10px", "margin-bottom":"5px"});
		$('.dataTables_filter').css({"margin-top" : "-3%", "width": "35%"});
		
		//for submit modal
		//$("#start_date").datetimepicker({format : 'yyyy-mm-dd hh:ii'});
		//$("#end_date").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
		
		$('#form_user').submit(function(e){
			e.preventDefault();

			$.ajax({
			  url: "<?=base_url('admin/user/saveUser')?>",
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
					$('#modalUser').modal('hide');
					swal({
						title: "Berhasil",
						text: "Berhasil menambahkan user",
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
		
		$('#username').change(function() { 
			$.ajax({
				data  : {username:$('#username').val(), <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
				url: "<?=base_url('admin/user/checkUser')?>",
				method: "POST",
				dataType: 'HTML',
				success: function(data) {
					if(data){
						const dat = JSON.parse(data);
						uke = dat[0].id_uke_bsdm;
						$("#nama").val(dat[0].nama);
						$("#uke").val(uke.substr(0, 4));
						$("#nama").prop('disabled', false);
						$("#uke").prop('disabled', false);
					}else{
						$("#nama").val("");
						$("#nama").prop('disabled', true);
						$("#uke").val("");
						$("#uke").prop('disabled', true);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					console.log(xhr.status);
					console.log(thrownError);
				}
			});
		});

	});
	
	function drawtable() {
		$('#tableusers').DataTable().ajax.reload();
	}

	function showModal() {
		$('.modal-title').text('Add User'); 
		$("#state").val(0);
		$("#iduser").val("");
		$("#username").val("");
		$("#nama").val("");
		$("#uke").val("");
		$("#role").val("");
				
		$('#modalUser').modal({backdrop: 'static', keyboard: false});
		$('#send').prop("disabled", false);
		$('#send').text('Simpan');
		$("#modalUser").modal("show");
	}

	function deluser(id) {
		//alert(id);
		swal({
		  title: "Apakah Anda Yakin ?",
		  text: "Klik hapus untuk menghapus user",
		  icon: "warning",
		  buttons: ["Batal", "Hapus"],
		  dangerMode: true,
		})
		.then((willDelete) => {
		  if (willDelete) {
			$.ajax({
				data  : {iduser:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
				url: "<?=base_url('admin/user/delUser')?>",
				method: "POST",
				dataType: 'json',
				success: function(data) {
				if(data){
					swal({
						title: "Success",
						text: "Berhasil menghapus user",
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

	function edituser(id) {
		$('#modalUser').on('shown.bs.modal', function(e){
			const buttonId = e.relatedTarget.id;
			$(this).find('.modal-title').text(`${buttonId}`);      
		});

		$.ajax({
			data  : {iduser:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			url: "<?=base_url('admin/user/getUser')?>",
			method: "POST",
			dataType: 'HTML',
			success: function(data) {
				const dat = JSON.parse(data);
				$("#state").val(1);
				$("#iduser").val(dat[0].iduser);
				$("#username").val(dat[0].username);
				$("#nama").val(dat[0].nama);
				$("#uke").val(dat[0].iduke);
				$("#role").val(dat[0].role);
				
				$('#send').prop("disabled", false);
				$('#send').text('Simpan');
				$('#modalUser').modal({backdrop: 'static', keyboard: false});
				$("#modalUser").modal("show");
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	}
	
</script>