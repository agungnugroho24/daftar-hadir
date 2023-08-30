<div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Template File</h4>
            </div>
            <div class="modal-body">
              <form id="form_template" >
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" class="form-control" id="template_file_id" name="template_file_id" value="<?=$template[0]['template_file_id']?>"  >
                <div class="form-group">
                    <select class="form-control m-bot15" id="type" name="type">
                        <label for="exampleInputEmail1">Tipe Berita</label>
                        <option value="0">---Pilih---</option>
                        <option value="JPT" <?=$template[0]['template_type'] == 'JPT' ? ' selected="selected"' : '';?> >JPT</option>
                        <option value="CPNS" <?=$template[0]['template_type'] == 'CPNS' ? ' selected="selected"' : '';?> >CPNS</option>
                        <option value="P3K" <?=$template[0]['template_type'] == 'P3K' ? ' selected="selected"' : '';?> >P3K</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama file" value="<?=$template[0]['template_file_name']?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">File</label><br>
                    <a href="<?=base_url()?><?=$template[0]['template_file_link']?>"><?php echo basename($template[0]['template_file_link']); ?></a>
                    <input type="file" class="form-control" id="file_template" name="file_template" placeholder="File">
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
  $("#start").datepicker({format: 'yyyy-mm-dd'});
  $("#end").datepicker({format: 'yyyy-mm-dd'});

  $('#form_template').submit(function(e){
    e.preventDefault();
    var dataForm = new FormData(this);
    $.ajax({
      url: "<?=base_url('admin/data/editTemplate')?>",
      type: 'POST',
      data: dataForm,
      async: false,
      beforeSend: function() { 
        $('#send').attr('disabled','disabled');
        $('#send').text('Loading ...');
      },
      success: function (data) {
        if (data=="1") {
          $('#modalTemplate').modal('hide');
          swal({
              title: "Berhasil",
              text: "Berhasil menambahkan data.",
              icon: "success",
          }).then((value) => {
            // location.reload();
            window.location = "<?=base_url('admin/data/template_file')?>";
          }); 
        }else if(data=="0"){
          swal("Error", "Tambah data gagal, Mohon Coba Lagi", "error");
          $('#send').prop("disabled", false);
          $('#send').text('Simpan');
        }else{
          swal("Error", data, "error");
          $('#send').prop("disabled", false);
          $('#send').text('Simpan');          
        }
      },
      cache: false,
      contentType: false,
      processData: false
    }); 
  });

</script>