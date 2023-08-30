<div class="modal fade" id="modalTemplate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Tambah Template File</h4>
            </div>
            <div class="modal-body">
              <form id="form_template" >
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="form-group">
                    <label for="exampleInputEmail1">Tipe</label>
                    <select class="form-control m-bot15" id="type" name="type">
                        <option value="0">---Pilih---</option>
                        <option value="JPT">JPT</option>
                        <option value="CPNS">CPNS</option>
                        <option value="P3K">P3K</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama file">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">File</label>
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

  $('#form_template').submit(function(e){
    e.preventDefault();
    var dataForm = new FormData(this);
    $.ajax({
      url: "<?=base_url('admin/data/addTemplate')?>",
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