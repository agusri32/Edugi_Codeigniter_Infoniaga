<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$produk_id 			= isset($qry_produk) ? $qry_produk->produk_id : "";
$produk_update  	= isset($qry_produk) ? $qry_produk->produk_update_date : date("d-m-Y h:m:s");;
$produk_nama 		= isset($qry_produk) ? $qry_produk->produk_nama : "";
$produk_keterangan  = isset($qry_produk) ? $qry_produk->produk_keterangan : "";
$produk_harga   	= isset($qry_produk) ? $qry_produk->produk_harga : "";
$produk_status  	= isset($qry_produk) ? $qry_produk->produk_status : "";

//menangani get
$tk = $this->input->get('tk');
$kl = $this->input->get('kl');
$jr = $this->input->get('jr');
$th = $this->input->get('th');
$st = $this->input->get('st');

if($produk_id === ""){
	$btn_value = "btn_simpan";
	$tanggal = "Tgl. Input";
}else{
	$btn_value = "btn_ubah";
	$tanggal = "Tgl. Update";
}

$random = rand();
$id = base64_encode($random."-".$produk_id);
$delete = base64_encode($random."-delete");

echo validation_errors();$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<script src='<?php echo base_url("assets/plugins/tinymce/tinymce.min.js"); ?>'></script>
<form class="form-horizontal" method="post" enctype="multipart/form-data" method="post" action="<?php echo site_url("class_item/form"); ?>">
<section class="content">
	
	<div class="row">
		<div class="col-md-5">
			<div class="box box-warning">
				<div class="box-body">
				
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Produk</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="txt_nama" id="txt_nama" value="<?php echo $produk_nama; ?>" required autofocus>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Harga (Rp)</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="txt_harga" id="txt_harga" value="<?php echo $produk_harga; ?>" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Status Produk</label>
						<div class="col-sm-8">
							<select name="opt_status" id="opt_status" class="form-control">
								<option value="1" <?php if($produk_status==1){ echo "selected=\"selected\""; } ?>>TERSEDIA</option>
								<option value="2" <?php if($produk_status==2){ echo "selected=\"selected\""; } ?>>TIDAK TERSEDIA</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Keterangan</label>
						<div class="col-sm-8">
							<input type="text" id="txt_keterangan" name="txt_keterangan" class="form-control" value="<?php echo $produk_keterangan; ?>">
						</div>
					</div>
					
					<div class="box-footer">
						<input type="hidden" name="hdd_produk_id" id="hdd_produk_id" value="<?php echo $this->input->get('id'); ?>">
						<input type="hidden" name="txt_produk_id" id="txt_produk_id" value="<?php echo $produk_id; ?>">
						
						<a href="<?php echo site_url('class_item') ?>" class="btn btn-danger"><i class='fa fa-arrow-left'></i></a>&nbsp;
						<button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>"  onClick="return cek_form()" class="btn btn-primary" <?php echo $konfirmasi;?>>SIMPAN</button>&nbsp;						
					</div>

				</div>
			</div>
		</div>
	</div>
	
</section>
</form>

<script type="text/javascript">	
$(document).ready(function(){
	$("#txt_harga").change(function(){
		tampil_nominal();
    });
	
	$("#txt_harga").keyup(function(){
		tampil_nominal();
    });
	
	tampil_nominal();
});

function tampil_nominal(){
	var harga=$("#txt_harga").val();
	var konversi=kurensi(harga);
	$('#txt_harga').val(konversi);
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