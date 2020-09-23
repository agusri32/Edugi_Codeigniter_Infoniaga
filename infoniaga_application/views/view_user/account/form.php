<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$member_id       = isset($qry_member) ? $qry_member->member_id    			  : "";
$member_nama     = isset($qry_member) ? ucwords($qry_member->member_nama)     : "";
$member_email    = isset($qry_member) ? strtolower($qry_member->member_email) : "";
$member_phone	 = isset($qry_member) ? strtolower($qry_member->member_phone) : "";
$member_kelamin  = isset($qry_member) ? ucwords($qry_member->member_kelamin)  : "";
$member_status   = isset($qry_member) ? ucwords($qry_member->member_status)   : "";
$member_stage    = isset($qry_member) ? ucwords($qry_member->member_stage)    : "";
$member_alamat   = isset($qry_member) ? ucwords($qry_member->member_alamat)   : "";

$btn_value = $member_id === "" ? "btn_simpan" : "btn_ubah";
echo validation_errors();$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="form-horizontal" method="post" action="<?php echo site_url("class_user/form"); ?>">
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-warning">
				<div class="box-body">
				
					<div class="form-group">
						<label class="col-sm-3 control-label">Nama Lengkap</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="member_nama" id="member_nama" value="<?php echo ucwords($member_nama); ?>" required autofocus>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Telephone</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="member_phone" id="member_phone" value="<?php echo $member_phone; ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label">Alamat</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="member_alamat" id="member_alamat" value="<?php echo $member_alamat; ?>" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Email</label>
						<div class="col-sm-8">
							<input type="hidden" name="hdd_member_id" id="hdd_member_id" value="<?php echo $member_id; ?>">
							<input type="text" class="form-control" name="member_email" id="member_email" value="<?php echo $member_email; ?>" required>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Jenis Kelamin</label>
						<div class="col-sm-8">
							<input name="member_kelamin" id="member_kelamin" <?php if($member_kelamin==1){ echo "checked"; } ?> type="radio" value="1" checked> Laki-Laki&nbsp;
							<input name="member_kelamin" id="member_kelamin" <?php if($member_kelamin==2){ echo "checked"; } ?> type="radio" value="2"> Perempuan
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Status</label>
						<div class="col-sm-8">
							<select name="member_status" id="member_status" class="form-control">
								<option value="1" <?php if($member_status==1){ echo "selected=\"selected\""; } ?>>PELANGGAN</option>
								<option value="2" <?php if($member_status==2){ echo "selected=\"selected\""; } ?>>CALON PELANGGAN</option>
							</select>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label">Canvasing</label>
						<div class="col-sm-8">
							<select name="member_stage" id="member_stage" class="form-control">
								<option value="1" <?php if($member_stage==1){ echo "selected=\"selected\""; } ?>>COLD</option>
								<option value="2" <?php if($member_stage==2){ echo "selected=\"selected\""; } ?>>WARM</option>
								<option value="3" <?php if($member_stage==3){ echo "selected=\"selected\""; } ?>>HOT</option>
							</select>
						</div>
					</div>
					
					<div class="box-footer">
						<a href="<?php echo site_url('class_user') ?>" class="btn btn-danger"><i class='fa fa-arrow-left'></i></a>&nbsp;
						<button type="submit" name="btn_simpan" id="btn_simpan" value="<?php echo $btn_value; ?>" class="btn btn-primary" <?php echo $konfirmasi;?>>SIMPAN</button>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</section>
</form>