<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   <b>Daftar Peserta Kegiatan <?=$agenda[0]['kegiatan']?></b>
                </header>
                <div class="panel-body">
                    <section id="unseen">
						<div class="table-responsive">
						<table class="table table-bordered table-striped table-condensed" id="tablepeserta">
							<thead>
							<tr>
								<th>No. </th>
							<?php
							if($form){
								foreach($form as $fr){
							?>
								<th><?=$fr['nama']?></th>
							<?php
								}
							?>
								<th>TTD</th>
							<?php
							}else{
							?>
								<th>Nama</th>
								<th>Jabatan</th>
								<th>Instansi / Unit Kerja</th>
								<th>Email</th>
								<th>Telephone</th>
								<th>TTD</th>
							<?php
							}
							?>
							</tr>
							</thead>
							<tbody>
							</tbody>
						</table
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
			.ttd{
				max-height: 100px;
				max-width: 200px;
			}
			</style>
			<div class="modal fade" id="modalSign" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Tanda Tangan Peserta</h4>
						</div>
						<div class="modal-body">
							<div id="sign"></div>
						</div>
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Keluar</button>
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
		$.fn.dataTable.ext.buttons.exportExcel = {
			text: ' Export Excel <i class="fa fa-file"></i>',
			action: function ( e, dt, node, config ) {
				window.location.href = "<?=base_url('peserta/export/')?><?=$idkeg_en?>";
			}
		};
		$.fn.dataTable.ext.buttons.exportPdf = {
			text: ' Export Pdf <i class="fa fa-file"></i>',
			action: function ( e, dt, node, config ) {
				window.location.href = "<?=base_url('peserta/topdf/')?><?=$idkeg_en?>";
			}
		};
		var button= [ { extend: 'exportExcel', className: 'btn-primary btn-sm' }, { extend: 'exportPdf', className: 'btn-primary btn-sm' }];
		$('#tablepeserta').DataTable({
			bFilter: true, 
			dom: 'Bfrtip',
			//scrollX: true,
			sAjaxDataProp:"",
			ajax: {
				"url": "<?=base_url('admin/data/peserta_json')?>",
				"type": "POST",
				"data" : {idagenda:"<?=$agenda[0]['idagenda']?>", <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
			},
			columns: [
							{"title": "No", "data": "no"},
					<?php
						if($form){
							foreach($form as $fr){
					?>
							{"title" : "<?=$fr['nama']?>", "data": "<?=$fr['nameform']?>"},	
					<?php
							}
					?>
							{"title": "TTD", "data" : "sign", 
									"render": function(data, type, row, meta){
									  if(type === 'display'){
										  data = '<img src="'+row.sign+'" class="ttd" alt="Tanda Tangan Peserta"/>';
									  }
									  return data;
									}}	
					<?php
						}else{
					?>
							{"title": "Nama","data":"nama"},
							{"title": "Jabatan" , "data":"jabatan"},
							{"title": "Instansi / Unit Kerja", "data" : "uke"},
							{"title": "Email" , "data":"email"},
							{"title": "Telephone" , "data":"phone"},
							{"title": "TTD", "data" : "sign", 
									"render": function(data, type, row, meta){
									  if(type === 'display'){
										  data = '<img src="'+row.sign+'" class="ttd" alt="Tanda Tangan Peserta"/>';
									  }
									  return data;
									}}	
					<?php
						}
					?>
					
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
		
	});
	
	function getsign(id) {
		
	}
</script>
      