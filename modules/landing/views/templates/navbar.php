	<header id="header" class="header fixed-top d-flex align-items-center">
		<div class="container d-flex align-items-center justify-content-between">

			<div id="logo">
				<h1><a href="<?=base_url()?>"><img src="<?=base_url('assets/landing/img/logo2.jpg')?>" class="img-fluid" alt="" title="" style="height:60px;width:160px;"/></a></h1>
			</div>

			<nav id="navbar" class="navbar">
				<ul>
					<li><a class="nav-link scrollto active" href="<?=base_url()?>">Home</a></li>
					<!--<li><a class="nav-link scrollto" href="listrapat.php">Daftar Rapat</a></li>-->
					<?php if ($this->session->userdata('islogin')==TRUE) { ?>
						<li><a class="nav-link scrollto" data-bs-toggle="modal" data-bs-target="#panduan" style="cursor: pointer;">Panduan</a></li>
					<?php }else{?>
						<li></li>
					<?php }?>
					<?php if ($this->session->userdata('islogin')==TRUE) { ?>
					<li class="dropdown"><a href="#"><span><?=$this->session->userdata('name')?></span> <i class="bi bi-chevron-down"></i></a>
						<ul>
							<li><a href="<?=base_url('admin/data')?>">Creator Panel</a></li>
							<li><a href="<?=base_url('admin/logout')?>">Logout</a></li>
						</ul>
					</li>
					<?php }else{?>
					<li><a class="nav-link scrollto" href="<?=base_url('admin/login')?>">Login</a></li>
					<?php }?>
				</ul>
				<i class="bi bi-list mobile-nav-toggle"></i>
			</nav><!-- .navbar -->

		</div>
	</header><!-- End Header -->