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
                <h4 class="modal-title">Ubah Kegiatan</h4>
            </div>
            <div class="modal-body">
              <form id="form_kegiatan" >
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" class="form-control" id="state" name="state" value="1">
                <input type="hidden" class="form-control" id="idagenda" name="idagenda" value="<?=$data[0]['idagenda']?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">Judul</label>
                    <input type="text" class="form-control" id="kegiatan" name="kegiatan" placeholder="Masukkan Judul Kegiatan" value="<?=$data[0]['kegiatan']?>">
                </div>
				<div class="form-group">
                    <label for="exampleInputEmail1">Alias (<?=base_url()?>{Alias Kegiatan})</label>
                    <input type="text" class="form-control" id="kegiatan" name="kegiatan" placeholder="Masukkan Alias Kegiatan Untuk Pemendekan URL">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Deskripsi</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" placeholder="Masukkan Deskripsi Kegiatan" value="<?=$data[0]['deskripsi']?>"></textarea>
                </div>
				<div class="form-group">
                    <label for="exampleInputEmail1">Mulai</label>
                    <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Mulai Kegiatan" autocomplete="off" value="<?=$data[0]['start_date']?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Selesai</label>
                    <input type="text" class="form-control" id="end_date" name="end_date" placeholder="Selesai Kegiatan" autocomplete="off" value="<?=$data[0]['end_date']?>">
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
<script type="text/javascript">
	$("#start_date").datetimepicker({format : 'yyyy-mm-dd hh:ii'});
	$("#end_date").datetimepicker({format: 'yyyy-mm-dd hh:ii'});

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
			if (data) {
			  $('#modalKegiatan').modal('hide');
			  swal({
				  title: "Berhasil",
				  text: "Berhasil menambahkan data.",
				  icon: "success",
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

</script>