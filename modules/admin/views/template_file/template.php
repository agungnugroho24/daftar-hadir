<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   <b>Daftar Template File</b>
                </header>
                <div class="panel-body">
                    <button class="btn btn-primary" style="position: absolute; z-index: 999;" onclick="showModal()"><i class="fa fa-plus"></i> Tambah Template FIle</button>
                    <section id="unseen" style="margin-top: -2%;">
                      <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>No. </th>
                            <th>Tipe</th>
                            <th>Nama</th>
                            <th>File</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($template) {
                          $no=1;
                          foreach ($template as $temp) { ?>
                            <tr>
                                <td><?=$no?></td>
                                <td><?=$temp['template_type']?></td>
                                <td><?=$temp['template_file_name']?></td>
                                <td><a href="<?=base_url()?><?=$temp['template_file_link']?>"><?php echo basename($temp['template_file_link']); ?></a></td>
                                <td>
                                  <button type="button" onclick="editTemplate('<?=$temp["template_file_id"]?>')" class="btn btn-primary" title="Edit Template File"><i class="fa fa-edit"></i></button>
                                  <button type="button" onclick="deleteTemplate('<?=$temp["template_file_id"]?>')" class="btn btn-danger" title="Delete Template File">X</button>
                                </td>
                            </tr>
                        <?php $no++;} } ?>
                        </tbody>
                    </table>
                    </section>
                </div>
            </section>
        </div>
    </div>
    <!-- page end-->
</section>

<div id="showModal"></div>

<script type="text/javascript">
  $(document).ready( function () {
    $('.table').DataTable({
		dom: 'Bfrtlp',
		buttons: [],
		lengthMenu: [
						[ 10, 25, 50, 100, 1000 ],
						[ '10', '25', '50', '100', 'All' ]
					]
	});
  });
  
  function showModal() {
    $.ajax({
        data  : {<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
        url: "<?=base_url('admin/data/modalAddTemplate')?>",
        method: "POST",
        dataType: 'HTML',
        success: function(data) {
            // alert(data);
            $('#showModal').html(data);
            $('#modalTemplate').modal({backdrop: 'static', keyboard: false});  
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
      });
  }

  function editTemplate(id) {
    $.ajax({
        data  : {id:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
        url: "<?=base_url('admin/data/modalEditTemplate')?>",
        method: "POST",
        dataType: 'HTML',
        success: function(data) {
            // alert(data);
            $('#showModal').html(data);
            $('#modalTemplate').modal({backdrop: 'static', keyboard: false});  
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        }
      });
  }

  function deleteTemplate(id) {
    swal({
      title: "Apakah Anda Yakin ?",
      text: "Klik Oke untuk melakukan penghapusan",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          data  : {id:id,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
          url: "<?=base_url('admin/data/deleteTemplate')?>",
          method: "POST",
          dataType: 'json',
          success: function(data) {
            swal({
              title: "Success",
              text: "Berhasil menghapus data",
              icon: "success"
            }).then((value) => {
              // location.reload();
              window.location = "<?=base_url('admin/data/template_file')?>";
            });  
          },
          error: function (xhr, ajaxOptions, thrownError) {
              console.log(xhr.status);
              console.log(thrownError);
          }
        });
      }
    });
  }

</script>
      