    <section id="hero">
		<div class="hero-container" data-aos="fade-in" style="padding-top:1%;">
			<h1 style="padding-top: -1%;">Daftar Hadir</h1>
			<h2 style="font-size: 1em;margin-top:-0.5%;margin-bottom:-0.1%;">Simple and Easy Everywhere Everytime</h2>
			<img src="<?=base_url('assets/landing/img/hero-img6.png')?>" alt="Hero Imgs" data-aos="zoom-out" data-aos-delay="100">
			<a href="#get-started" class="btn-get-started scrollto">Rapat Terbaru</a>
		</div>
	</section><!-- End Hero Section -->
    <!-- ======= Get Started Section ======= -->
    <section id="get-started" class="padd-section text-center" style="">

		<div class="container" data-aos="fade-up">
			<div class="section-title text-center">
			<h2>Rapat Terbaru </h2>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<?php
				if($rapat){
				?>
				<div class="col-md-6 col-lg-12" data-aos="zoom-in" data-aos-delay="100">
					<div class="feature-block">
						<div class="bd-example">
							<table id="rapat1" class="table table-striped table-hover" style="width:100%">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Nama Rapat</th>
										<th scope="col">Waktu</th>
										<th scope="col">Action</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$i=0;
									foreach($rapat as $row){
										
								?>
									<tr>
										<th scope="row"><?=$i+1?></th>
										<td><?=$row['kegiatan']?></td>
										<td><?=$row['start_date']?></td>
										<td><a href="<?=base_url().''.$row['alias']?>"><button class=" btn-sm btn-primary">Hadir</button></a></td>
									</tr>
									<?php
									$i++;
									}
								?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php
				}else{
				?>
				<div class="col-md-6 col-lg-12" data-aos="zoom-in" data-aos-delay="200">
					<div class="feature-block">
						<div class="alert alert-primary" role="alert">
						<div class="text-center">
							<h4 class="alert-heading" style="margin-top: 1%;"><i class="bx bxs-info-circle"></i> Belum Ada Rapat Terbaru</h4>
						</div>
						</div>
					</div>
				</div>
				<?php
				}
				?>
			</div>
		</div>
    </section><!-- End Get Started Section -->
<script>
	$(document).ready(function() {
    	$('#rapat1').DataTable({
			"pageLength": 5,
			"bLengthChange": false,
		});
	} );
</script>