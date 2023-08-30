<!DOCTYPE html>
<html lang="en">

<?=$head?>
  <body>

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <?=$navbar?>
      </header>
      <!--header end-->
      <!--sidebar start-->
	  <?php
	  if($this->session->userdata('role')== "1"){
		  $marginleft = "180px;";
	  ?>
      <aside>
        <?=$sidebar?>
      </aside>
	  <?php
	  }else{
		  $marginleft = "0px;";
	  }
	  ?>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content" style="margin-left:<?=$marginleft;?>">
        <?=$content?>
      </section>
      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
        <?=$footer?>
      </footer>
      <!--footer end-->
      <div id="showModal"></div>
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?=base_url('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?=base_url('assets/js/jquery.scrollTo.min.js')?>"></script>
    <script src="<?=base_url('assets/js/jquery.nicescroll.js')?>" type="text/javascript"></script>

    <script src="<?=base_url('assets/js/jquery-ui-1.9.2.custom.min.js')?>"></script>
    <script class="include" type="text/javascript" src="<?=base_url('assets/js/jquery.dcjqaccordion.2.7.js')?>"></script>

  <!--custom switch-->
  <script src="<?=base_url('assets/js/bootstrap-switch.js')?>"></script>
  <!--custom tagsinput-->
  <script src="<?=base_url('assets/js/jquery.tagsinput.js')?>"></script>
  <!--custom checkbox & radio-->
  <script type="text/javascript" src="<?=base_url('assets/js/ga.js')?>"></script>

  <!-- Datepicker -->
  <script type="text/javascript" src="<?php echo base_url()?>assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

  <script type="text/javascript" src="<?=base_url('assets/assets/ckeditor/ckeditor.js')?>"></script>

  <script type="text/javascript" src="<?=base_url('assets/assets/bootstrap-inputmask/bootstrap-inputmask.min.js')?>"></script>
  <script src="<?=base_url('assets/js/respond.min.js')?>" ></script>
  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>assets/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" language="javascript" src="<?php echo base_url()?>assets/datatables/dataTables.buttons.min.js"></script>


  <!--common script for all pages-->
    <script src="<?=base_url('assets/js/common-scripts.js')?>"></script>

  
  </body>
</html>
