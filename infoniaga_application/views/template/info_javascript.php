<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Security</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="ROBOTS" content="NOINDEX,NOFOLLOW,NOARCHIVE,NOODP,NOYDIR,NOSNIPPET">
	<link rel="shortcut icon" href="<?php echo base_url("assets/images/favicon.ico"); ?>" type="image/x-icon">

	<script language="javascript">
		document.onkeydown = function(e) {
			if (e.ctrlKey && e.keyCode === 85){
				return false;
			} else {
				return true;
			}
		};
	</script>

	<meta http-equiv="refresh"/>
</head>

<body>
	<center>
		<br>
		<img src="<?php echo base_url("assets/images/lock-security.png"); ?>" alt="Bintang Pelajar">
		<br>
		<font face="verdana" size="2"><br>
			<noscript>
				Silahkan aktifkan setting Javascript di web Browser Anda! 
			</noscript>
		</font>
		<a href="<?php echo site_url("auth"); ?>" style="text-decoration:none"><font color="blues" face="verdana" size="2">Kembali Login</font></a>
	</center>
</body>