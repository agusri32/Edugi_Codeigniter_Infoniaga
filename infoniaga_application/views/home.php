<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo $title; ?>
	</h1>
</section>

<?php
$random = rand();
$mode = base64_encode($random."-add");
?>

<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo $jml_lead;?></h3>
              <p>CALON PELANGGAN</p>
            </div>
            <div class="icon">
              <i class="ion ion-leaf"></i>
            </div>
            <a href="javascript:;" onClick=account_function('<?php echo $mode;?>'); class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo $jml_account;?></h3>
              <p>PELANGGAN</p>
            </div>
            <div class="icon">
              <i class="ion ion-person"></i>
            </div>
            <a href="javascript:;" onClick=account_function('<?php echo $mode;?>'); class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo $jml_product;?></h3>
              <p>PRODUK</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="javascript:;" onClick=product_function('<?php echo $mode;?>'); class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
		
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo $jml_opportunity;?></h3>
              <p>PELUANG</p>
            </div>
            <div class="icon">
              <i class="ion ion-stats-bars"></i>
            </div>
            <a href="javascript:;" onClick=opportunity_function('<?php echo $mode;?>'); class="small-box-footer">Lihat Data <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
	</div>
	
</section>

<script type="text/javascript">
function opportunity_function(m) 
{
    var base_url = "class_judul/index?m="+m;
    window.location.href = "<?php echo site_url(); ?>"+base_url;
}

function product_function(m) 
{
    var base_url = "class_item/index?m="+m;
    window.location.href = "<?php echo site_url(); ?>"+base_url;
}

function account_function(m) 
{
    var base_url = "class_member/index?m="+m;
    window.location.href = "<?php echo site_url(); ?>"+base_url;
}
</script>