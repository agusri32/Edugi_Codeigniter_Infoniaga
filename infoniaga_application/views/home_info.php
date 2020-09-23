<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<section class="content">
	<div class="row">
	
		<div class="col-md-5">
			<div class="box box-warning">
				<div class="box-body box-profile">	
					<center>
						<img class="profile-user-img img-responsive" src="<?php echo base_url('assets/images/logo-crm.jpg') ?>" width="70%">
					</center><br>
					<p><b>InfoNiaga</b> merupakan <i>prototype</i> dari aplikasi CRM(Customer Relationship Management). Didalamnya mengelola data calon pelanggan, pelanggan, produk yang ditawarkan, penawaran yang diberikan kepada calon pelanggan maupun pelanggan yang eksis, serta laporan yang dibutuhkan.<br><br>
					Aplikasi ini dapat digunakan untuk kegiatan <i>canvassing</i> bagi teman-teman sales, maupun owner dari pemilik usaha yang mencari prospek calon pelanggan. Agar data calon pelanggan tercatat dengan rapi di sistem.<br><br>
					Semoga aplikasi sederhana ini dapat bermanfaat untuk teman-teman dan Anda semua. Jika ada pertanyaan dan saran-saran dapat disampaikan via email <i><u>sumarna.agus@gmail.com</u></i><br>
					</p>
				</div>
			</div>
			
		</div>
	</div>
	<?php /*
	<div class="row">
		<div class="col-md-6">
			<div class="box box-danger">
				<div class="box-body box-profile">
					<table id="my-siswa" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nama Owner</th>
								<th><center>Jumlah Data Siswa</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($qry_siswa as $row)
							{
								?>
								<tr>
									<td><?php echo strtoupper($row->member_update_by); ?></td>
									<td><?php echo "<font color='brown'>".strtoupper($row->member_nama)."</font>"; ?></td>
									<td><center><?php echo $row->jumlah; ?></center></td>
								</tr>
								<?php
							}	
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-body box-profile">
					<table id="my-soal" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th style="width: 4%;">ID</th>
								<th>Nama Owner</th>
								<th><center>Jumlah Data Soal</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($qry_soal as $row)
							{
								?>
								<tr>
									<td><?php echo strtoupper($row->produk_update_by); ?></td>
									<td><?php echo "<font color='brown'>".strtoupper($row->member_nama)."</font>"; ?></td>
									<td><center><?php echo $row->jumlah; ?></center></td>
								</tr>
								<?php
							}	
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="box box-success">
				<div class="box-body box-profile">
					<table id="my-kegiatan" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th style="width: 4%;">ID</th>
								<th>Nama Owner</th>
								<th><center>Jumlah Data Kegiatan</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($qry_judul as $row)
							{
								?>
								<tr>
									<td><?php echo strtoupper($row->order_update_by); ?></td>
									<td><?php echo "<font color='brown'>".strtoupper($row->member_nama)."</font>"; ?></td>
									<td><center><?php echo $row->jumlah; ?></center></td>
								</tr>
								<?php
							}	
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-warning">
				<div class="box-body box-profile">
					<table id="my-hasil" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th style="width: 4%;">ID</th>
								<th>Nama Owner</th>
								<th><center>Jumlah Data Hasil Ujian</center></th>
							</tr>
						</thead>
						<tbody>
							<?php
							foreach($qry_hasil as $row)
							{
								?>
								<tr>
									<td><?php echo strtoupper($row->member_update_by); ?></td>
									<td><?php echo "<font color='brown'>".strtoupper($row->member_nama)."</font>"; ?></td>
									<td><center><?php echo $row->jumlah; ?></center></td>
								</tr>
								<?php
							}	
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php */ ?>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function(){
	$('#my-siswa').DataTable({responsive: false,
        "order": [[ 2, "desc" ]],
		"scrollX" : true,
    });
	
	$('#my-soal').DataTable({responsive: false,
        "order": [[ 2, "desc" ]],
		"scrollX" : true,
    });
	
	$('#my-kegiatan').DataTable({responsive: false,
        "order": [[ 2, "desc" ]],
		"scrollX" : true,
    });
	
	$('#my-hasil').DataTable({responsive: false,
       "order": [[ 2, "desc" ]],
	   "scrollX" : true,
    });
});
</script>