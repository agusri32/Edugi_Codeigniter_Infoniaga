<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$member_id 	   = isset($qry_member) ? $qry_member->member_id : "";
$member_status = isset($qry_member) ? ucwords($qry_member->member_status) : "";
$member_nama   = isset($qry_member) ? ucwords($qry_member->member_nama)   : "";
$member_email  = isset($qry_member) ? ucwords($qry_member->member_email)  : "";
$member_phone  = isset($qry_member) ? ucwords($qry_member->member_phone)  : "";
$account 	   = $this->input->get('id');

if($member_status==1){
	$status = "Pelanggan";
}else{
	$status = "Calon Pelanggan";
}

$random = rand();
$mode   = base64_encode($random."-add");
$etbl_admin = base64_encode($random."-".$member_id);
?>

<form class="form-horizontal">
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-body box-profile">
						
					<div class="form-group">
						<label class="col-sm-2 control-label">Nama Lengkap</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo strtoupper($member_nama); ?>" disabled>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Info Kontak</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo strtolower($member_email)." / ".strtolower($member_phone); ?>" disabled>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo strtoupper($status); ?>" disabled>
						</div>
					</div>
	
				</div>
			</div>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-body box-profile">

					<table id="my-table" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th style="width: 4%;">ID</th>
								<th>Judul Peluang</th>
								<th>Account</th>
								<th>Status Peluang</th>
								<th>Jumlah</th>
								<th>Keterangan</th>
							</tr>
						</thead>
					</table>
					
					<br>
					<a href="<?php echo site_url('class_user') ?>" class="btn btn-danger" title="Kembali"><i class='fa fa-arrow-left'></i></a>
					<a onClick=add_function('<?php echo $mode;?>','<?php echo $account;?>'); class="btn btn-success">NEW OPPORTUNITY</a>

				</div>
			</div>
		</div>
	</div>	
	
</section>
</form>

<script type="text/javascript">
$(document).ready(function(){
    $('#my-table').DataTable({responsive: false,
        "processing" : true,
        "serverSide" : true,
        "scrollX" : true,
		"order": [[ 1, "desc" ]],
        "ajax" : {
            url: "<?php echo site_url("class_user/view_ajax?id=".$account); ?>",
            type: "post",
            error: function() {
                $(".my-table-error").html("");
                $("#my-table").append('<tbody class="my-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                $("#my-table_processing").css("display","none");
            }
        },
		"language": {
            
			"sZeroRecords":  "Tidak ada data di database",
			"sProcessing":   "Sedang memproses...",
			"sLengthMenu":   "Tampilkan _MENU_ data",
			"sInfo":         "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
			"sInfoEmpty":    "Menampilkan 0 sampai 0 dari 0 data",
			"sSearch":       "Cari data:",
			"oPaginate": {
				"sFirst":    "Pertama",
				"sPrevious": "Sebelumnya",
				"sNext":     "Selanjutnya",
				"sLast":     "Terakhir"
			}
		},
        "columnDefs": [{
            "targets": [0,4,5], "orderable": false
        }]
    });
});

function add_function(m,id) 
{
    var base_url = "class_judul/form?m="+m+"&id="+id;
    window.open("<?php echo site_url(); ?>"+base_url);
}

function order_function(m,id) 
{
	var base_url = "class_judul/form_pilih?m="+m+"&id="+id;
	window.open("<?php echo site_url(); ?>"+base_url);
}

function user_function(m,id) 
{
	var base_url = "class_user/form?m="+m+"&id="+id;
	window.open("<?php echo site_url(); ?>"+base_url);
}
</script>