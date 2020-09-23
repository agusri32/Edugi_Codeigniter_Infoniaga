<?php
$this->load->view('template/head');
?>

<script src="<?php echo base_url("assets/plugins/datatables/media/js/jszip.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/pdfmake.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/buttons.print.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/vfs_fonts.js"); ?>"></script>

<link rel="stylesheet" href="<?php echo base_url("assets/plugins/datatables/media/css/jquery.dataTables.min.css"); ?>" />
<link rel="stylesheet" href="<?php echo base_url("assets/plugins/datatables/media/css/responsive.dataTables.css"); ?>" />	
<link rel="stylesheet" href="<?php echo base_url("assets/plugins/datatables/media/css/font-awesome.min.css"); ?>" />
<link rel="stylesheet" href="<?php echo base_url("assets/plugins/datatables/media/css/buttons.dataTables.min.css"); ?>" />

<script src="<?php echo base_url("assets/plugins/datatables/media/js/jquery-1.11.3.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/jquery.dataTables.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/dataTables.responsive.min.js"); ?>"></script>

<script src="<?php echo base_url("assets/plugins/datatables/media/js/dataTables.buttons.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/buttons.html5.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/plugins/datatables/media/js/buttons.colVis.min.js"); ?>"></script>

<script src="<?php echo base_url("assets/plugins/countdown/jquery.countdown.min.js"); ?>"></script>

<?php
$this->load->view('template/topbar');
$this->load->view('template/sidebar');
$this->load->view($page);
$this->load->view('template/js');
?>