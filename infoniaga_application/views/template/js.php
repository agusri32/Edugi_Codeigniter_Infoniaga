</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.0
    </div>
   Copyright &copy; <?php echo date("Y"); ?> <a href="http://ri32.wordpress.com" target="_blank">Ri32 Web Project</a>
</footer>
</div>

<script src='<?php echo base_url('assets/adminlte/AdminLTE-2.0.5/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>'></script>
<script src="<?php echo base_url('assets/adminlte/AdminLTE-2.0.5/bootstrap/js/bootstrap.min.js') ?>" type="text/javascript"></script>
<script src="<?php echo base_url('assets/adminlte/AdminLTE-2.0.5/dist/js/app.min.js') ?>" type="text/javascript"></script>

<link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" >
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
  $(function () {
    $("#compose-textarea").wysihtml5();
  });
</script>