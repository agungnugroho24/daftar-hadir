<div class="modal fade" id="uploadDokumen" tabindex="-1" aria-labelledby="uploadDokumenLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="uploadDokumenLabel">Upload Dokumen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body px-10">            
            <table class="table table-striped" id="tabel_data_dukung">
              <thead>
                <tr>
                    <th class="text-left">Nama Berkas</th>
                    <th class="text-left">File</th>
                    <th class="text-left">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($lists as $list) { ?>
                  <tr>
                    <td><?=$list['doc_type_name']?> <?=$list['id']=='1' ?  '(Format JPG)' : '(Format PDF)' ?></td>
                    <td>
                      <div id="upload_file<?=$list['id']?>">
                      <?php 
                      if ($daftar==0) {
                        if($list['doc_pelamar_id']!=NULL){ ?>
                        <a href="<?=base_url($list['doc_link'].'/'.$list['doc_name'])?>" target="_blank"><?=$list['doc_name']?></a>
                      <?php }else{ ?>
                        <input class="file" id="file<?=$list['id']?>" onchange="upload(<?=$list['id']?>)" name="file<?=$list['id']?>" type="file" >
                      <?php } }else{
                        if($list['doc_pelamar_id']!=NULL){ ?>
                        <a href="<?=base_url($list['doc_link'].'/'.$list['doc_name'])?>" target="_blank"><?=$list['doc_name']?></a>
                      <?php } } ?>
                      </div>
                    </td>                      
                    <?php 
                    if($daftar==0){ 
                      if($list['doc_pelamar_id']!=NULL){ ?>
                        <td><button onclick="showUpload(<?=$list['id']?>)">Ubah</button></td>   
                      <?php }else{ echo '<td></td>'; } }else{ echo '<td>Sudah terdaftar</td>';} ?>            
                  </tr>
                <?php } ?>    
              </tbody>
            </table>
            <div class="text-center">
              <button class="btn btn-dodgerblue" data-dismiss="modal">Keluar</button>
            </div>
          </div>
        </div>
      </div>
    </div>

<script type="text/javascript">
  function upload(id) {
    var file_data = $('input[name=file'+id+']').prop('files')[0];   
    var dataForm = new FormData();            
    dataForm.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');                   
    dataForm.append('files', file_data);      
    dataForm.append('type', id);

    var sizeInMB = (file_data.size / (1024*1024)).toFixed(2);
    if (sizeInMB > 5) {
      swal("Error", "File lebih dari 5MB", "error");      
    }else{
      $("#tabel_data_dukung").LoadingOverlay("show");
      $.ajax({
        url: "<?=base_url('landing/data/dataDukung')?>",
        type: 'POST',
        data: dataForm,
        async: false,
        success: function (data) {
          $("#tabel_data_dukung").LoadingOverlay("hide", true);
          if (data=="1") {
            $('#uploadDokumen').modal('hide');          
            swal({
                title: "Berhasil",
                text: "Berhasil upload dokumen.",
                icon: "success",
            }).then((value) => {
              modalDokumen();
            });
          }else if(data=="0"){
            swal("Error", "Tambah data gagal, Mohon Coba Lagi", "error");
          }else{
            swal("Error", data, "error");
          }
        },
        cache: false,
        contentType: false,
        processData: false
      }); 
    }
  }

  function showUpload(id) {
    $("#upload_file"+id).html('<input class="file" id="file'+id+'" onchange="upload('+id+')" name="file'+id+'" type="file" >');
  }
</script>