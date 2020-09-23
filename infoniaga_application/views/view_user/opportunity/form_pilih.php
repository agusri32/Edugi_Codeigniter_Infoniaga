<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="content-header">
	<h1>
		<?php echo ucwords($title); ?>
	</h1>
</section>

<?php
$member_id  = $this->session->userdata("memberId");
$order_id = isset($qry_judul) ? $qry_judul->order_id : "";

$order_biaya1  = isset($qry_judul) ? ucwords($qry_judul->order_biaya1) : "";
$order_biaya2  = isset($qry_judul) ? ucwords($qry_judul->order_biaya2) : "";
$order_biaya3  = isset($qry_judul) ? ucwords($qry_judul->order_biaya3) : "";

$status_order  = isset($qry_judul) ? ucwords($qry_judul->sdr_ket) 			: "";
$order_tanggal = isset($qry_judul) ? ucwords($qry_judul->order_tanggal) 	: "";
$order_status  = isset($qry_judul) ? ucwords($qry_judul->order_status) 	    : "";
$order_update  = isset($qry_judul) ? ucwords($qry_judul->order_update_date) : date("d-m-Y h:m:s");

$order_keterangan  = isset($qry_judul) ? ucwords($qry_judul->order_keterangan) : "";

if($order_id === ""){
	$btn_value = "btn_simpan";
	$tanggal = "Tgl. Input";
}else{
	$btn_value = "btn_ubah";
	$tanggal = "Tgl. Update";
}

$biaya_tambahan = $order_biaya1+$order_biaya2+$order_biaya3;

$parameter = "AND detail_update_by=".$member_id." AND detail_order=".$order_id;
$get_nominal = $this->mm->get_all_data_by_param("v_tbl_detail","*","detail", $parameter);
if(!empty($get_nominal)){
	$nominal=0;
	foreach($get_nominal as $rows)
	{
		$jumlah  = $rows->detail_jumlah;
		$harga   = $rows->detail_harga;
		$total   = $jumlah*$harga;
		$nominal = $nominal+$total;
	}
}else{
	$nominal="0";
}

$random = rand();
$ejudul = base64_encode($random."-".$order_id);
		
echo validation_errors();$konfirmasi = $btn_value === "btn_ubah" ? "onClick=\"return confirm('Apakah Anda Yakin?')\"" : "";
?>

<form class="form-horizontal">
<section class="content">

	<div class="row">
		<div class="col-md-12">
			<div class="box box-warning">
				<div class="box-body box-profile">
						
					<input type="hidden" name="txt_order_id" id="txt_order_id" value="<?php echo $order_id; ?>">
			
					<input type="hidden" id="hdd_id" value="<?php echo $this->input->get('id'); ?>">
					<input type="hidden" id="hdd_m"  value="<?php echo $this->input->get('m'); ?>">
					<input type="hidden" id="hdd_a"  value="<?php echo $this->input->get('a'); ?>">
					<input type="hidden" id="hdd_th" value="<?php echo $this->input->get('th'); ?>">

					<input type="hidden" name="op_status"   value="1">
					<input type="hidden" name="opt_kelas"   value="0">
					<input type="hidden" name="opt_jurusan" value="0">
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Judul Peluang</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo strtoupper($order_keterangan); ?>" disabled>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Tanggal</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo $order_tanggal; ?>" disabled>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Status</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="<?php echo $status_order; ?>" disabled>
						</div>
					</div>
					<hr>
					
					<div class="form-group">
						<label class="col-sm-2 control-label">Jumlah Biaya</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="Rp <?php echo number_format($biaya_tambahan,0,',','.'); ?>" disabled>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-2 control-label">Jumlah Transaksi</label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="Rp <?php echo number_format($nominal,0,',','.'); ?>" disabled>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-sm-2 control-label"><font color='grey'>TOTAL NOMINAL</font></label>
						<div class="col-sm-4">
							<input type="text" class="form-control" value="Rp <?php echo number_format($nominal+$biaya_tambahan,0,',','.'); ?>" disabled>
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
								<th>ID</th>
								<th>Produk Tersedia</th>
								<th>Harga (Rp)</th>
								<th style="width: 5%;">Option</th>
							</tr>
						</thead>
					</table>
					
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="box box-danger">
				<div class="box-body box-profile">
					
					<table id="my-produk" class="display compact nowrap" width="100%">
						<thead>
							<tr>
								<th>No</th>
								<th>Produk Terpilih</th>
								<th>Harga (Rp)</th>
								<th>Jumlah</th>
								<th style="width: 5%;">Option</th>
							</tr>
						</thead>
					</table>	
					
					<br>
					<a href="<?php echo site_url('class_judul') ?>" class="btn btn-sm btn-danger" title="Kembali"><i class='fa fa-arrow-left'></i></a>
					<input type="button" name="btn_submit" id="btn_submit" value="UPDATE JUMLAH" class="btn btn-sm btn-success">

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
		"order": [[ 0, "asc" ]],
        "ajax" : {
            url: "<?php echo site_url("class_judul/pilih_produk_ajax?ejd=".$ejudul); ?>",
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
            "targets": [2,3], "orderable": false
        }]
    });
	
	var table = $('#my-produk').DataTable({responsive: false,
        "processing" : true,
        "serverSide" : true,
		"scrollX" : true,
		"order": [[ 0, "asc" ]],
        "ajax" : {
            url: "<?php echo site_url("class_judul/list_produk_ajax?ejd=".$ejudul); ?>",
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
            "targets": [2,3], "orderable": false
        }]
    });
	
	$('#btn_submit').click( function() {
		if (confirm('Apakah Anda Yakin?'))
		{
			$.ajax({
				url: "<?php echo site_url("class_judul/update_ajax?"); ?>",
				data: "&"+table.$('#hdd_detail_id').serialize()+"&"+table.$('#txt_jumlah').serialize(),
				success: function(data)
				{
					<?php
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil disimpan");
					$alert = base64_encode($rand."-warning");
					?>
					window.location.href ="<?php echo site_url("class_judul/form_pilih?m=".$this->input->get('m')."&id=".$this->input->get('id'));?>";
				}
			});
			return false;
		}
		else
		{}
	});
});

function refresh_function(id) 
{
	var base_url = "class_judul/form_pilih?id="+id;
	window.location.href = "<?php echo site_url(); ?>"+base_url;
}

function view_function(m,id) 
{
	var base_url = "class_item/form?m="+m+"&id="+id;
	window.open("<?php echo site_url(); ?>"+base_url);
}

function viewklik_function(m,id) 
{
	var base_url = "class_item/form?m="+m+"&id="+id;
	window.open("<?php echo site_url(); ?>"+base_url);
}

function add_function(m,id) 
{
	if (confirm('Apakah Anda yakin?'))
    {
        var tahun = $('#hdd_th').val();
		var base_url = "class_judul/submit?m="+m+"&id="+id+"&ejd="+'<?php echo $ejudul; ?>'+"&th="+tahun;
        window.location.href = "<?php echo site_url(); ?>"+base_url;
    }else{}
}

function addklik_function(m,id) 
{
	if (confirm('Apakah Anda yakin?'))
    {
        var tahun = $('#hdd_th').val();
		var base_url = "class_judul/submit?m="+m+"&id="+id+"&ejd="+'<?php echo $ejudul; ?>'+"&th="+tahun;
        window.open("<?php echo site_url(); ?>"+base_url);
    }else{}
}

function delete_function(m,id) 
{
	if (confirm('Apakah Anda yakin?'))
    {
        var tahun = $('#hdd_th').val();
		var base_url = "class_judul/batal_produk?m="+m+"&id="+id+"&ejd="+'<?php echo $ejudul; ?>'+"&th="+tahun;
        window.location.href = "<?php echo site_url(); ?>"+base_url;
    }else{}
}

function delklik_function(m,id) 
{
	if (confirm('Apakah Anda yakin?'))
    {
        var tahun = $('#hdd_th').val();
		var base_url = "class_judul/batal_produk?m="+m+"&id="+id+"&ejd="+'<?php echo $ejudul; ?>'+"&th="+tahun;
        window.open("<?php echo site_url(); ?>"+base_url);
    }else{}
}
</script>