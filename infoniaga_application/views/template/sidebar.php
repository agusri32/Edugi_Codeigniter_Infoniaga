<?php $uri = $this->uri->segment(1)==""?"dashboard":$this->uri->segment(1); ?>
<?php if($uri=="class_info" || $uri=="class_siswa" || $uri=="class_item" || $uri=="class_judul" || $uri=="class_hasil"){ $flag = "active treeview"; }else{ $flag = "treeview"; } ?>

<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MENU UTAMA</li>
			<li <?php if($uri=="home"){ echo "class='active'"; } ?>><a href="<?php echo base_url("home"); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li <?php if($uri=="class_user"){  echo "class='active'"; } ?>><a href="<?php echo base_url("class_user");  ?>"><i class="fa fa-user"></i>  Account</a></li>
			<li <?php if($uri=="class_item"){  echo "class='active'"; } ?>><a href="<?php echo base_url("class_item");  ?>"><i class="fa fa-tags"></i>  Product</a></li>
			<li <?php if($uri=="class_judul"){ echo "class='active'"; } ?>><a href="<?php echo base_url("class_judul"); ?>"><i class="fa fa-send"></i>  Opportunity</a></li>
			<li <?php if($uri=="class_info"){  echo "class='active'"; } ?>><a href="<?php echo base_url("class_info");  ?>"><i class="fa fa-book"></i>  Panduan Aplikasi</a></li>
        </ul>
    </section>
</aside>
<div class="content-wrapper">