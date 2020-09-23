<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-body box-profile">
					
					<?php
					if(isset($message))
					{
					?>
					<div class="alert alert-<?php echo $alert; ?> alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					<p><i class="icon fa fa-info"></i><?php echo $message;?></p>
					</div>
					<?php
					}
					?>
					
					<?php
					$random = rand();
					$mode = base64_encode($random."-add");
					?>
                    
					<button type="submit" class='btn btn-sm btn-primary pull-left' onClick=add_function('<?php echo $mode;?>'); id="submit" name="submit" value="submit">TAMBAH DATA</button>
					<br><br>

					<table id="my-table" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th style="width: 6%;">Option</th>
								<th style="width: 4%;">ID</th>
								<th>Nama Produk</th>
								<th>Harga (Rp)</th>
								<th>Status Produk</th>
								<th>Keterangan</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function(){
	$('#my-table').DataTable({responsive: false,
        "processing" : true,
        "serverSide" : true,
        "scrollX" : true,
		"order": [[ 1, "desc" ]],
        "ajax" : {
            url: "<?php echo site_url("class_item/produk_ajax"); ?>",
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
            "targets": [0], "orderable": false
        }]
    });
});

function add_function(m) 
{
    var base_url = "class_item/form?m="+m;
    window.location.href = "<?php echo site_url(); ?>"+base_url;
}

function edit_function(m,id) 
{
	var base_url = "class_item/form?m="+m+"&id="+id;
	window.location.href = "<?php echo site_url(); ?>"+base_url;
}

function editklik_function(m,id) 
{
	var base_url = "class_item/form?m="+m+"&id="+id;
	window.open("<?php echo site_url(); ?>"+base_url);
}

function del_function(m,id) 
{
    if (confirm('Apakah Anda Yakin?'))
    {
        var base_url = "class_item/delete_produk?m="+m+"&id="+id;
        window.location.href = "<?php echo site_url(); ?>"+base_url;
    }else{}
}

function delklik_function(m,id) 
{
    if (confirm('Apakah Anda Yakin?'))
    {
        var base_url = "class_item/delete_produk?m="+m+"&id="+id;
		window.open("<?php echo site_url(); ?>"+base_url);
    }else{}
}
</script>