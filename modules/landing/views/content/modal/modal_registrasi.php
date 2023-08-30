    <div class="modal fade" id="modalRegistrasi" tabindex="-1" aria-labelledby="cetakBuktiRegistrasiLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="cetakBuktiRegistrasiLabel">Pilih Formasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-10">
            <form id="form_daftar">
              <div class="row">
                <div class="col-12">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <div class="form-group">
                    <label >Pilih formasi jabatan</label>
                    <?php if ($daftar==0) { ?>
                      <select class="form-control form-line-bolder" name="jabatan" id="jabatan">
                        <option value="0">--Pilih--</option>
                        <?php foreach ($vacancy as $vacan) { ?>
                          <option value="<?=$vacan['id']?>"><?=$vacan['vacancy_name']?></option>
                        <?php } ?>
                      </select>
                    <?php }else{ ?>
                        <input type="text" class="form-control" disabled="disabled" value="<?=$daftar['vacancy_name']?>">
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <?php if ($daftar==0) { ?>
                  <button class="btn btn-dodgerblue" type="submit" id="send" name="submit">Daftar</button>
                <?php }else{ ?>
                  <a href="<?=base_url('bukti')?>" class="btn btn-dodgerblue" target="_blank">Cetak Bukti Pendaftaran</a>
                <?php } ?>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">  
  $('#form_daftar').submit(function(e){
    e.preventDefault();
    var jabatan=$('#jabatan').val();
    if (jabatan=='0') {
      swal("Error", "Mohon pilih formasi", "error");
    }else{
      swal({
        title: "Apakah Anda Yakin?",
        text: "Pastikan semua data anda sudah terisi dengan benar",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((reconfirm) => {
        if(reconfirm) {
          swal({
            title: "Apakah Anda Yakin?",
            text: "Setelah memilih Formasi, data Anda tidak dapat diubah",
            icon: "warning",
            buttons: true,
            dangerMode: true,
          }).then((register) => {
              if (register){
                  var dataForm = new FormData(this);
                  $.ajax({
                    url: "<?=base_url('landing/data/registerFormasi')?>",
                    type: 'POST',
                    data: dataForm,
                    async: false,
                    beforeSend: function() { 
                      $('#send').attr('disabled','disabled');
                      $('#send').text('Loading ...');
                    },
                    success: function (data) {
                      if (data=="1") {
                        $('#modalRegistrasi').modal('hide');
                        swal({
                            title: "Berhasil",
                            text: "Berhasil melakukan pendaftaran.",
                            icon: "success",
                        }).then((value) => {
                          location.reload();
                        }); 
                      }else if (data=="2") {                      
                        swal("Error", "Mohon cek kembali kelengkapan data dan dokumen anda", "error");
                        $('#send').prop("disabled", false);
                        $('#send').text('Simpan');
                      }else{
                        swal("Error", "Registrasi gagal, Mohon Coba Lagi", "error");
                        $('#send').prop("disabled", false);
                        $('#send').text('Simpan');
                      }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                  });
              }else{
                  swal("Silahkan melakukan pengecekan ulang pada data anda");
              }
          });
        }else{
            swal("Silahkan melengkapi data anda terlebih dahulu");
        }
      });
    }
  });
</script>