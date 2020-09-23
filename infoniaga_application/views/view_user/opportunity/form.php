<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$get_id = $this->input->get('id');
if(isset($get_id) && $get_id!=""){
	$dec_n = base64_decode($get_id);
	$n = explode("-",$dec_n);
	$account_id = $n[1];
}else{
	$account_id = "";
}

$order_id      = isset($qry_judul) ? $qry_judul->order_id : "";
$order_judul   = isset($qry_judul) ? ucwords($qry_judul->order_judul)  : "";
$order_status  = isset($qry_judul) ? ucwords($qry_judul->order_status) : "";
$order_member  = isset($qry_judul) ? ucwords($qry_judul->order_member) : $account_id;

$order_biaya1  = isset($qry_judul) ? ucwords($qry_judul->order_biaya1) : "";
$order_biaya2  = isset($qry_judul) ? ucwords($qry_judul->order_biaya2) : "";
$order_biaya3  = isset($qry_judul) ? ucwords($qry_judul->order_biaya3) : "";

$order_update  	   = isset($qry_judul) ? ucwords($qry_judul->order_update_date) : date("d-m-Y h:m:s");
$order_tanggal     = isset($qry_judul) ? ucwords($qry_judul->order_tanggal)     : "";
$order_keterangan  = isset($qry_judul) ? ucwords($qry_judul->order_keterangan)  : "";

if($order_id === ""){
	$btn_value = "btn_simpan";
	$tanggal = "Tgl. Input";
}else{
	$btn_value = "btn_ubah";
	$tanggal = "Tgl. Update";
}

$random = rand();
$id = base64_encode($random."-".$order_id);
			
echo validation_errors();$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="form-horizontal" enctype="multipart/form-data" method="post" action="<?php echo site_url("class_judul/form"); ?>">
<section class="content">
	<div class="row">
		<div class="col-md-8">
			<div class="box box-warning">
				<div class="box-body">

					<div class="form-group">
						<label class="col-sm-2 control-label">Judul</label>
						<div class="col-sm-4">
							<input type="text" id="txt_judul" name="txt_judul" class="form-control" value="<?php echo $order_judul; ?>" required>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-4">
							<input type="text" id="txt_tanggal" name="txt_tanggal" class="form-control" value="<?php echo $order_tanggal; ?>" autocomplete="off">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Account</label>
						<div class="col-sm-4">
							<select name="opt_member" id="opt_member" class="form-control">
								<?php
								foreach($qry_member as $row)
								{
									$selected = $order_member == $row->member_id ? "selected=\"selected\"" : "";
									
									if($row->member_status==1){
										$status = "Pelanggan";
									}else{
										$status = "Calon Pelanggan";
									}
									?>
									<option value="<?php echo $row->member_id; ?>" <?php echo $selected; ?>><?php echo strtoupper($row->member_nama." - ".$status); ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<select name="opt_status" id="opt_status" class="form-control">
								<?php
								foreach($qry_sdr as $rows)
								{
									$selecteds = $order_status == $rows->sdr_id ? "selected=\"selected\"" : "";
									?>
									<option value="<?php echo $rows->sdr_id; ?>" <?php echo $selecteds; ?>><?php echo strtoupper($rows->sdr_ket); ?></option>
									<?php
								}
								?>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Keterangan</label>
						<div class="col-sm-4">
							<input type="text" id="txt_keterangan" name="txt_keterangan" class="form-control" value="<?php echo $order_tanggal; ?>">
						</div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Biaya Tambahan 1</label>
						<div class="col-sm-4">
							<input type="text" id="order_biaya1" name="order_biaya1" class="form-control" value="<?php echo $order_biaya1; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Biaya Tambahan 2</label>
						<div class="col-sm-4">
							<input type="text" id="order_biaya2" name="order_biaya2" class="form-control" value="<?php echo $order_biaya2; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Biaya Tambahan 3</label>
						<div class="col-sm-4">
							<input type="text" id="order_biaya3" name="order_biaya3" class="form-control" value="<?php echo $order_biaya3; ?>">
						</div>
					</div>

					<div class="box-footer">
						<input type="hidden" name="txt_order_id" id="txt_order_id" value="<?php echo $order_id; ?>">
						<a href="<?php echo site_url('class_judul') ?>" class="btn btn-danger"><i class='fa fa-arrow-left'></i></a>&nbsp;
						<button type="submit" name="btn_simpan" id="btn_simpan" onClick="return cek_form()" value="<?php echo $btn_value; ?>" class="btn btn-primary" <?php echo $konfirmasi;?>>SIMPAN</button>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>
</form>

<script>
$(document).ready(function(){
	$( "#txt_tanggal" ).datepicker({
		dateFormat: "yy-mm-dd"
	});
	
	$("#order_biaya1").change(function(){
		tampil_biaya1();
    });
	
	$("#order_biaya1").keyup(function(){
		tampil_biaya1();
    });
	
	$("#order_biaya2").change(function(){
		tampil_biaya2();
    });
	
	$("#order_biaya2").keyup(function(){
		tampil_biaya2();
    });
	
	$("#order_biaya3").change(function(){
		tampil_biaya3();
    });
	
	$("#order_biaya3").keyup(function(){
		tampil_biaya3();
    });
	
	tampil_biaya1();
	tampil_biaya2();
	tampil_biaya3();
});

function tampil_biaya1(){
	var mode=$("#order_biaya1").val();
	var konversi=kurensi(mode);
	$('#order_biaya1').val(konversi);
}

function tampil_biaya2(){
	var akses=$("#order_biaya2").val();
	var konversi=kurensi(akses);
	$('#order_biaya2').val(konversi);
}

function tampil_biaya3(){
	var parent=$("#order_biaya3").val();
	var konversi=kurensi(parent);
	$('#order_biaya3').val(konversi);
}

function kurensi(nilai) 
{
	bk=nilai.replace(/[^\d]/g,"");
	ck="";
	panjangk=bk.length;
	j=0;
	for (i=panjangk; i > 0; i--) 
	{
		j=j + 1;
		if (((j % 3) == 1) && (j != 1)) 
		{
			ck=bk.substr(i-1,1) + "." + ck;
			xk=bk;
		} 
		else 
		{
			ck=bk.substr(i-1,1) + ck;
			xk=bk;
		}
	}
	return ck;
}
</script>