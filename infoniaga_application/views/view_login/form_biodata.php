<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$member_id       = isset($qry_member) ? $qry_member->member_id    : "";
$member_email    = isset($qry_member) ? $qry_member->member_email : "";
$member_nama     = isset($qry_member) ? ucwords($qry_member->member_nama)    : "";
$member_kelamin  = isset($qry_member) ? ucwords($qry_member->member_kelamin) : "";
$member_status   = isset($qry_member) ? ucwords($qry_member->member_status)  : "";
$member_alamat   = isset($qry_member) ? ucwords($qry_member->member_alamat)  : "";
$member_phone	 = isset($qry_member) ? ucwords($qry_member->member_phone)   : "";
$member_username = isset($qry_member) ? $qry_member->member_username : "";
$member_password = isset($qry_member) ? $qry_member->member_password : "";

$btn_value = $member_id === "" ? "btn_simpan" : "btn_ubah";
echo validation_errors();$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<section class="content">
	<?php
	if(isset($message))
	{
		?>
		<div class="row">
			<div class="col-md-5">
				<div class="alert alert-<?php echo $alert; ?>" alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<i class="icon fa fa-check"></i><?php echo $message;?>
				</div>
			</div>
		</div>
		<?php
	}
	?>
	
	<div class="row">
		<div class="col-md-5">
			<div class="box box-warning">
				<form class="form-horizontal" method="post" action="<?php echo site_url("auth/update"); ?>">
					<div class="box-body">
					
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama Lengkap</label>
							<div class="col-sm-8">
								<input type="hidden" name="hdd_member_id" id="hdd_member_id" value="<?php echo $member_id; ?>">
								<input type="text" class="form-control" name="member_nama" id="member_nama" value="<?php echo ucwords($member_nama); ?>" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Alamat Email</label>
							<div class="col-sm-8">
								<input type="email" class="form-control" name="member_email" id="member_email" value="<?php echo $member_email; ?>" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama Kota</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="member_alamat" id="member_alamat" value="<?php echo $member_alamat; ?>" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Telephone</label>
							<div class="col-sm-8">
								<input type="text" class="form-control" name="member_phone" id="member_phone" value="<?php echo $member_phone; ?>" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Jenis Kelamin</label>
							<div class="col-sm-8">
								<input name="member_kelamin" id="member_kelamin" <?php if($member_kelamin==1){ echo "checked"; } ?> type="radio" value="1" required> Laki-Laki&nbsp;
								<input name="member_kelamin" id="member_kelamin" <?php if($member_kelamin==2){ echo "checked"; } ?> type="radio" value="2"> Perempuan
							</div>
						</div>
						
						<div class="box-footer">
							<a href="<?php echo site_url('home') ?>" class="btn btn-danger">Batal</a>&nbsp;
							<button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="btn btn-primary" <?php echo $konfirmasi;?>>Simpan</button>
						</div>
						
						<br>
						<font color='blues'>Mohon Maaf, untuk data yang tidak valid akan kami hapus!</font>
						
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function()
{
	$("#member_phone").change(function(){
        tampil_kelas();
    });
	
	tampil_kelas();
});

function tampil_kelas()
{
	var tingkat = $("#member_phone").val();
	var kelas_ku = $("#hdd_kelas").val();
        
	$.ajax({
		url: "<?php echo site_url("biodata/kelas_ajax"); ?>",
		data: "tingkat="+tingkat+"&kelas_ku="+kelas_ku,
		success:function(data){
			$("#member_kelas").html(data);
		}
	});
	
	if(tingkat!=4){
		$('#member_status').prop('disabled',true);
		$('#member_status').val(0);
	}else{
		$('#member_status').prop('disabled',false);
	}
}
</script>