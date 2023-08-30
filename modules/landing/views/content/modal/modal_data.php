<div class="modal fade" id="isianDataUtama" tabindex="-1" aria-labelledby="isianDataUtamaLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="isianDataUtamaLabel">Isian Data Utama</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-10">
            <form id="form_data">
              <div class="row">
                <div class="col-12 col-sm-6">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <div class="form-group">
                    <label for="nama">Nama lengkap</label>
                    <input type="text" class="form-control form-line-bolder" name="name" value="<?=$user['name']?>">
                  </div>
                  <div class="form-group">
                    <label for="nip">NIP (tanpa spasi)</label>
                    <input type="text" class="form-control form-line-bolder" name="nip" value="<?=$user['nip']?>">
                  </div>
                  <div class="form-group">
                    <label for="ktp">NIK</label>
                    <input type="text" class="form-control form-line-bolder" name="nik" value="<?=$user['nik']?>" maxlength="16">
                  </div>
                  <div class="form-group">
                    <label for="jk">Jenis kelamin</label>
                    <select class="form-control form-line-bolder" name="jns_kelamin">
                      <option value="L" <?=$user['jns_kelamin'] == 'L' ? ' selected="selected"' : '';?>>Laki-laki</option>
                      <option value="P" <?=$user['jns_kelamin'] == 'P' ? ' selected="selected"' : '';?>>Perempuan</option>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <div class="form-group">
                        <label for="tml">Tempat lahir</label>
                        <input type="text" class="form-control form-line-bolder" name="tmp_lahir" value="<?=$user['tmp_lahir']?>">
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="form-group">
                        <label for="tgl">Tanggal lahir</label>
                        <input type="text" class="form-control form-line-bolder" id="tgl_lahir" name="tgl_lahir" value="<?=$user['tgl_lahir']?>">
                      </div>
                    </div>
                  </div>
                </div>                
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control form-line-bolder" name="email" disabled="disabled" value="<?=$user['email']?>">
                  </div>
                  <div class="form-group">
                    <label for="npwp">NPWP</label>
                    <input type="text" class="form-control form-line-bolder" name="npwp" value="<?=$user['npwp']?>">
                  </div>
                  <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" class="form-control form-line-bolder" name="alamat" value="<?=$user['alamat']?>">
                  </div>
                  <div class="form-group">
                    <label for="nope">Nomor HP</label>
                    <input type="text" class="form-control form-line-bolder" name="hp" value="<?=$user['hp']?>">
                  </div>
                  <div class="form-group">
                    <label for="note">Nomor Telepon</label>
                    <input type="text" class="form-control form-line-bolder" name="no_telp" value="<?=$user['no_telp']?>">
                  </div>
                </div>
              </div>
              <hr>
              <p>Pangkat dan Jabatan Terakhir</p>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="pg">Pangkat/Golongan</label>
                    <select class="form-control form-line-bolder" name="pangkat">
                      <option value="">--Pilih--</option>
                      <?php foreach ($pangkat as $key) { ?>
                        <option value="<?=$key['pangkat_id']?>" <?=$user['pangkat_id'] == $key['pangkat_id'] ? ' selected="selected"' : '';?>><?=$key['pangkat_name']?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="uk">Unit Kerja</label>
                    <input type="text" class="form-control form-line-bolder" name="uke" value="<?=$user['uke']?>">
                  </div>
                  <!--<div class="form-group">
                    <label for="uk">No. SK</label>
                    <input type="text" class="form-control form-line-bolder" name="no_sk" value="<?=$user['no_sk']?>">
                  </div>-->
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" class="form-control form-line-bolder" name="jabatan" value="<?=$user['jabatan']?>">
                  </div>                  
                  <div class="form-group">
                    <label for="instansi">Instansi</label>
                    <input type="text" class="form-control form-line-bolder" name="instansi" value="<?=$user['instansi']?>">
                  </div>
                  <!--<div class="form-group">-->
                  <!--  <label for="uk">&nbsp;&nbsp;</label>-->
                  <!--</div>-->
                </div>
              </div>
              <hr>
              <p>Pendidikan Terakhir</p>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="univ">Universitas</label>
                    <input type="text" class="form-control form-line-bolder" name="universitas" value="<?=$user['universitas']?>">
                  </div>
                  <div class="form-group">
                    <label for="noijasah">No. Ijazah</label>
                    <input type="text" class="form-control form-line-bolder" name="no_ijasah" value="<?=$user['no_ijasah']?>">
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="strata">Strata</label>
                    <select class="form-control form-line-bolder" name="strata">
                      <option value="">--Pilih--</option>
                      <?php foreach ($strata as $key) { ?>
                        <option value="<?=$key['strata_id']?>" <?=$user['strata_id'] == $key['strata_id'] ? ' selected="selected"' : '';?>><?=$key['strata_name']?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="thnlulun">Tahun Lulus</label>
                    <input type="text" class="form-control form-line-bolder" name="thn_lulus" value="<?=$user['thn_lulus']?>">
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button class="btn btn-dodgerblue" type="submit" name="submit" id="send">Kirim</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
<script type="text/javascript">
  $("#tgl_lahir").datepicker({format: 'yyyy-mm-dd'});

  $('#form_data').submit(function(e){
    e.preventDefault();
    var dataForm = new FormData(this);
    $.ajax({
      url: "<?=base_url('landing/data/updateData')?>",
      type: 'POST',
      data: dataForm,
      async: false,
      beforeSend: function() { 
        $('#send').attr('disabled','disabled');
        $('#send').text('Loading ...');
      },
      success: function (data) {
        if (data!="0") {
          $('#isianDataUtama').modal('hide');
          swal({
              title: "Berhasil",
              text: "Berhasil update data.",
              icon: "success",
          }).then((value) => {
            location.reload();
          }); 
        }else{
          swal("Error", "Tambah data gagal, Mohon Coba Lagi", "error");
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