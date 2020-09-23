<!DOCTYPE html>
<html>
<head>
<title>INFONIAGA - Website Customer Relationship Management</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Website Mini CRM(Customer Relationship Management) untuk SALES & UKM" />
<link rel="shortcut icon" href="<?php echo base_url("assets/images/favicon.ico"); ?>" type="image/x-icon">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<link href="<?php echo base_url("assets/login/css/style.css"); ?>" rel="stylesheet" type="text/css" media="all" />
<link href="<?php echo base_url("assets/login/css/css/font-google.css"); ?>" rel="stylesheet" type="text/css" media="all" />
<script src="<?php echo base_url("assets/login/js/jquery.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/login/js/easyResponsiveTabs.js"); ?>" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function () {
	$('#horizontalTab').easyResponsiveTabs({
		type: 'default',          
		width: 'auto', 
		fit: true  
	});
});
</script>

</head>
<body>
	<div class="main">
		<h1>INFONIAGA</h1> 
		<div class="main-info">
			<div class="sap_tabs">
				<div id="horizontalTab" style="display: block; width: 100%; margin: 0px;">
					<ul class="resp-tabs-list">
						<li class="resp-tab-item" aria-controls="tab_item-0"><h2><span>Login</span></h2></li>
						<li class="resp-tab-item" aria-controls="tab_item-1"><span>Daftar</span></li> 
					</ul>	
					<div class="clear"> </div>	
					<div class="resp-tabs-container">
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-0">
							<div class="agileits-login">
								<form action="<?php echo site_url("auth/validate_credential"); ?>" method="post">
									<input type="text" class="email" name="txt_member_name" placeholder="Email" required=""/>
									<input type="password" class="password" name="txt_member_password" placeholder="Password" required=""/>
									<input type="hidden" name="btn_login" value="btn_login">
									<center><font color='yellow' face='verdana'>
									<?php
									$get_a = $this->input->get("a");
									if(isset($get_a) && $get_a!=""){
										$dec_a = base64_decode($get_a);
										$a = explode("-",$dec_a);
										$alert = $a[1];
										
										if($alert=="success"){ echo "Berhasil Registrasi & Silahkan Login"; }
										if($alert=="warning"){ echo "Gagal Registrasi & Silahkan Coba Kembali"; }
										if($alert=="failed"){  echo "Username atau Password Anda Salah"; }
									}
									?></font></center>
									<div class="w3ls-submit">
										<div class="submit-text">
											<input type="submit" value="LOGIN"> 
										</div>	
									</div>	
								</form>
							</div> 
						</div>
						<div class="tab-1 resp-tab-content" aria-labelledby="tab_item-1">
							<div class="login-top sign-top">
								<div class="agileits-login">
									<form action="<?php echo site_url("auth/save"); ?>" method="post">
										<input type="hidden" name="member_kelamin" id="member_kelamin" value="0">
										<input type="text" name="txt_nama" placeholder="Nama Lengkap" required>
										<input type="text" name="txt_member_name" class="email" placeholder="Alamat Email" required>
										<input type="password" name="txt_member_password" class="password"  placeholder="Password" required>	
										<input type="hidden" name="btn_simpan" value="btn_simpan">
										<div class="w3ls-submit">
											<div class="submit-text">
												<input class="register" type="submit" value="REGISTER">  
											</div>	
										</div>
									</form> 
								</div>  
							</div>
						</div>
					</div>	
				</div>
				<div class="clear"> </div>
			</div>  
		</div>
	</div>	
</body>
</html>