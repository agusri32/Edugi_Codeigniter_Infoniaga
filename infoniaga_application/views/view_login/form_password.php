<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo $title; ?>
	</h1>
</section>

<?php 
$a=$this->input->get("a");
if(isset($a))
{
	$get_a = $this->input->get("a");
	$dec_a = base64_decode("$get_a");
	$a = explode("-",$dec_a);
	$alert = $a[1];
}
else
{	
	if(empty($status)){$alert="";}else{$alert=$status;}
}
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
				<form class="form-horizontal" method="post" action="<?php echo site_url("auth/update_password"); ?>">
					<div class="box-body">
						
						<div class="form-group">
							<label class="col-sm-4 control-label">Password Lama</label>
							<div class="col-sm-7">
								<input type="password" class="form-control" placeholder="Password Lama" id="txt_old_password" name="txt_old_password" value="<?php echo set_value('txt_old_password'); ?>" autofocus="autofocus">
								<?php echo form_error('txt_old_password'); ?>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label">Password Baru</label>
							<div class="col-sm-7">
								<input type="password" class="form-control" placeholder="Password Baru" id="txt_new_password" name="txt_new_password" value="<?php echo set_value('txt_new_password'); ?>" autofocus="autofocus">
								<?php echo form_error('txt_new_password'); ?>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-4 control-label">Konfirmasi Password</label>
							<div class="col-sm-7">
								<input type="password" class="form-control" placeholder="Konfirmasi Password" id="txt_confirm_password" name="txt_confirm_password" value="<?php echo set_value('txt_confirm_password'); ?>" autofocus="autofocus">
								<?php echo form_error('txt_confirm_password'); ?>
							</div>
						</div>
						
					</div>
					<div class="box-footer">
						<a href="<?php echo site_url('home') ?>" class="btn btn-danger">Batal</a>&nbsp;
						<button type="submit" class="btn btn-primary" name="btn_simpan" value="btn_simpan">Simpan</button>
					</div>
				</form>
				
			</div>
		</div>
	</div>
</section>