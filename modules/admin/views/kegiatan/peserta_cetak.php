<style>
th, td {
	padding : 10px;
}
.ttd{
	max-height: 100px;
    max-width: 200px;
}
</style>
<section class="wrapper">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <div class="panel-heading" style="text-align: center;">
                   <b style="font-weight: bold;">Daftar Peserta Kegiatan <?=$kegiatan[0]['kegiatan']?></b>
				   <p><?=format_hari($kegiatan[0]['start_date']);?>, <?=format_tgl($kegiatan[0]['start_date'])?> s/d <?=format_tgl($kegiatan[0]['end_date'])?></p>
                </div>
                <div class="panel-body">
                    <section id="unseen">
						<table class="table table-bordered table-striped table-condensed" id="tablepeserta">
							<thead>
							<tr>
								<th>No. </th>
							<?php
							if($form){
								foreach($form as $fr){
							?>
								<th><?=$fr['nama']?></th>
							<?php
								}
							?>
								<th>TTD</th>
							<?php
							}else{
							?>
								<th>Nama</th>
								<th>Jabatan</th>
								<th>Instansi / Unit Kerja</th>
								<th>Email</th>
								<th>Telephone</th>
								<th>TTD</th>
							<?php
							}
							?>
							</tr>
							</thead>
							<tbody>
							<?php
							if($form){
								if($kehadiran){
									$i=0;
							
									foreach($kehadiran as $kh){
										$i++;
							?>
									<tr>
										<td style="padding: 10px; width:20px"><?=$i?></td>
							<?php 
										$content = unserialize($kh['custom']);
										foreach($form as $fr){
											
							?>	
										<td style="padding: 10px; width:150px"><?=$content[$fr['nameform']]?></td>
							<?php
										}
							?>			<td style="padding: 10px;"><img src="<?=$content['sign']?>" class="ttd" /></td>
									</tr>
							<?php
									}
								}else{
							?>
								<tr>
									<td style="padding: 10px;"> --- </td>
							<?php
									foreach($form as $fr){
							?>
									<td style="padding: 10px;"> --- </td>
							<?php
									}
							?>
									<td style="padding: 10px;"> --- </td>
								</tr>
							<?php
								}
							}else{
							?>
							
							<?php
								if($kehadiran){
									$i=0;
									foreach($kehadiran as $us){
									$i++;	
							?>	
									<tr>
										<td style="padding: 10px;"><?=$i?></td>
										<td style="padding: 10px; width:150px"><?=$us['nama']?></td>
										<td style="padding: 10px; width:150px"><?=$us['jabatan']?></td>
										<td style="padding: 10px; width:150px"><?=$us['uke']?></td>
										<td style="padding: 10px; width:150px"><?=$us['email']?></td>
										<td style="padding: 10px; width:150px"><?=$us['phone']?></td>
										<td style="padding: 10px;"><img src="<?=$us['sign']?>" class="ttd" /></td>
									</tr>
							<?php	
									}
								}else{
							?>
									<tr>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
										<td style="padding: 10px;"> --- </td>
									</tr>
							<?php 
								}
							}
							?>
							</tbody>
						</table>
                    </section>
                </div>
            </section>
		</div>
    </div>
    <!-- page end-->
</section>