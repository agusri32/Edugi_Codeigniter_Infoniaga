<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Class_judul extends CI_Controller
{
    /*
	 * @autor KangAgus <sumarna.agus@gmail.com>
     */
	
	function __construct()
	{
		parent::__construct();
		$this->cek_login();
		$this->output->enable_profiler(FALSE);
		$this->load->model("master_model", "mm");
	}
	
	function index()
	{
		if($this->input->get("m") && $this->input->get("a"))
		{
			$get_a = $this->input->get("a");
			$dec_a = base64_decode("$get_a");
			$a = explode("-",$dec_a);
			$alert = $a[1];
			
			$get_m = $this->input->get("m");
			$dec_m = base64_decode("$get_m");
			$m = explode("-",$dec_m);
			$message = $m[1];
			
			$data["alert"] = $alert;
			$data["message"] = $message;
			
			if(is_string($alert)==FALSE || ($alert!='success' && $alert!='warning') || is_string($message)==FALSE){
				redirect(site_url("auth/warning"));
			}
		}
		
		//cek biodata
		$member_id = $this->session->userdata("memberId");
		$cari_data = $this->mm->get_data_by_id("member_id",$member_id,"tbl_member","member");
		
		$nama    = $cari_data->member_nama;
		$alamat  = $cari_data->member_alamat;
		$email   = $cari_data->member_email;
		$phone   = $cari_data->member_phone;
		
		if($nama=="" || $alamat=="" || $email=="" || $phone==""){
			redirect(site_url("auth/update_biodata"));
		}
		
		$data["page"]  = "view_user/opportunity/home";
        $data["title"] = "Peluang Penjualan & Kerjasama";
		$this->load->view('template/template', $data);
	}
	
	function order_ajax()
	{
		$requestData= $_REQUEST;
	
		$columns = array( 
			0 => '',
			1 => 'order_id', 
			2 => 'order_judul',
			3 => 'order_member',
			4 => 'order_status',
			5 => '',
			6 => '',
			7 => 'order_tanggal',
		);
		
		$member_id = $this->session->userdata("memberId");
		$myparam = "order_update_by=".$member_id;
		
		$get_parent=$this->session->userdata('session_parent');
		if(isset($get_parent) && $get_parent!=""){
			$dec_m = base64_decode($get_parent);
			$m = explode("-",$dec_m);
			$parent_id = $m[1];
			$filter_parent = "order_biaya3=".$parent_id." AND ";
		}else{
			$filter_parent = "";
		}
		
		$res_tot  = $this->mm->count_all_data("v_tbl_order",$myparam,$filter_parent."order");
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		if( !empty($requestData['search']['value']) )
		{
			$where = $myparam." AND (order_keterangan LIKE '%".$requestData['search']['value']."%')";
			
			$res = $this->mm->get_search_data("v_tbl_order", $where, $filter_parent."order", $order_by, $limit, $offset);
			
			$res_filtered_tot = $this->mm->count_all_data("v_tbl_order", $where, $filter_parent."order");
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_search_data("v_tbl_order", $myparam, $filter_parent."order", $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}
		
		$data = array();if(!empty($res)){
		foreach($res as $row)
		{
			$random = rand();
			$id = base64_encode($random."-".$row->order_id);
			$us = base64_encode($random."-".$row->order_member);
			$edit = base64_encode($random."-edit");
			$delete = base64_encode($random."-delete");

			if($row->order_status=="1"){ $order_status="<span class='label label-warning'>PENAWARAN</span>";}
			if($row->order_status=="2"){ $order_status="<span class='label label-warning'>PRESENTASI</span>";}
			if($row->order_status=="3"){ $order_status="<span class='label label-warning'>NEGOSIASI</span>";}
			if($row->order_status=="4"){ $order_status="<span class='label label-primary'>PENGERJAAN</span>";}
			if($row->order_status=="5"){ $order_status="<span class='label label-primary'>PENGIRIMAN</span>";}
			if($row->order_status=="6"){ $order_status="<span class='label label-success'>SERAHTERIMA</span>";}
			if($row->order_status=="7"){ $order_status="<span class='label label-success'>TERKIRIM</span>";}
			if($row->order_status=="8"){ $order_status="<span class='label label-danger'>PEMBATALAN</span>";}
			
			$parameter = "AND detail_update_by=".$member_id." AND detail_order=".$row->order_id;
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
			
			$biaya_1  = $row->order_biaya1;
			$biaya_2  = $row->order_biaya2;
			$biaya_3  = $row->order_biaya3;
			$jmltotal = $nominal+$biaya_1+$biaya_2+$biaya_3;
			
			$cekdata="detail_order=".$row->order_id."";
			$cek_produk=$this->mm->check_duplicate("tbl_detail","detail",$cekdata);
			$jumlah=$cek_produk->jml;
			
			$nestedData = array();
			$nestedData[] = "
			<a href='javascript:void(0)' oncontextmenu=\"produkklik_function('".$edit."','".$id."');  return false;\" onClick=produk_function('".$edit."','".$id."');  class='btn btn-sm btn-success' title=\"View Product\"><i class='fa fa-tags'></i></a>
			<a href='javascript:void(0)' oncontextmenu=\"editklik_function('".$edit."','".$id."');  return false;\" onClick=edit_function('".$edit."','".$id."');  class='btn btn-sm btn-info' title=\"Edit\"><i class='fa fa-check'></i></a>
			<a href='javascript:void(0)' oncontextmenu=\"delklik_function('".$delete."','".$id."'); return false;\" onClick=del_function('".$delete."','".$id."'); class='btn btn-sm btn-danger' title=\"Delete\"><i class='fa fa-trash'></i></a>
			";
			$nestedData[] = $row->order_id;
			$nestedData[] = "<a href='javascript:;'>".strtoupper($row->order_judul)."</a>";
			$nestedData[] = "<a href='javascript:;' onClick=user_function('".$edit."','".$us."'); >".strtoupper($row->member_nama)."</a>";
			$nestedData[] = $order_status;
			$nestedData[] = "Rp ".number_format($jmltotal,0,',','.');
			$nestedData[] = $jumlah." Produk";
			$nestedData[] = "<i>".ucwords($row->order_tanggal)."</i>";
			
			$data[] = $nestedData;}
		}
		
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval($tot_data),
			"recordsFiltered" => intval($tot_filtered),
			"data"            => $data
		);
		echo json_encode($json_data);
	}
	
	function pilih_produk_ajax()
	{
		$requestData= $_REQUEST;
		$columns = array( 
			0 => 'produk_id', 
			1 => 'produk_nama',
			2 => '',
			3 => '',
			4 => '',
			5 => '',
			6 => ''
		);

		$member_id = $this->session->userdata("memberId");
		
		$userku="produk_update_by=".$member_id." AND ";
		$parameter=$userku." produk";
		
		$users="produk_update_by=".$member_id."";
		$parameters=$users."";
	
		$res_tot = $this->mm->count_all_data("v_tbl_produk", NULL, $parameter);
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		if( !empty($requestData['search']['value']) )
		{
			$where = "(produk_nama LIKE '%".$requestData['search']['value']."%')";
			$res = $this->mm->get_search_data("v_tbl_produk", $where, $parameter, $order_by, $limit, $offset);
			
			$res_filtered_tot = $this->mm->count_all_data("v_tbl_produk", $where, $parameter);
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_search_data("v_tbl_produk", $parameters, "produk" , $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}
		
		$jd = $this->input->get("ejd");
		$jd_decrypt = base64_decode("$jd");
		$judul = explode("-",$jd_decrypt);
		$order_id=$judul[1];
		
		$data = array();if(!empty($res)){
		foreach($res as $row)
		{
			$random = rand();
			$produk_id=$row->produk_id;
			$id = base64_encode($random."-".$produk_id);
			$edit = base64_encode($random."-edit");
			$delete = base64_encode($random."-delete");
			
			$produk_nama = $row->produk_nama;
			$jml = strlen($produk_nama);
			if($jml>70){
				$namaproduk = substr($produk_nama,0,60)."...";
			}else{
				$namaproduk = $produk_nama;
			}
			
			$cekdata="detail_order=".$order_id." AND detail_produk=".$produk_id."";
			$cek_produk=$this->mm->check_duplicate("tbl_detail","detail",$cekdata);
			if($cek_produk->jml === "0"){
				$produkAction="";
			}else{
				$produkAction="disabled";
			}
			
			$nestedData = array();
			$nestedData[] = $produk_id;
			$nestedData[] = strtoupper($namaproduk);
			$nestedData[] = number_format($row->produk_harga,0,',','.');

			$nestedData[] = "
			<a href='javascript:void(0)' oncontextmenu=\"viewklik_function('".$edit."','".$id."'); return false;\" onClick=view_function('".$edit."','".$id."'); class='btn btn-sm btn-info' title=\"Lihat Produk\"><i class='fa fa-search'></i></a>
			<a href='javascript:void(0)' ".$produkAction." oncontextmenu=\"addklik_function('".$edit."','".$id."'); return false;\" onClick=add_function('".$edit."','".$id."');  class='btn btn-sm btn-success' title=\"Pilih Produk\"><i class='fa fa-check'></i></a>
			";
			
			$data[] = $nestedData;}
		}
		
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval($tot_data),
			"recordsFiltered" => intval($tot_filtered),
			"data"            => $data
		);
		echo json_encode($json_data);
	}
	
	function list_produk_ajax()
	{
		$requestData= $_REQUEST;
		$columns = array( 
			0 => 'produk_id', 
			1 => 'produk_nama',
			2 => '',
			3 => '',
			4 => '',
			5 => '',
			6 => ''
		);
		
		$jd = $this->input->get("ejd");
		$jd_decrypt = base64_decode("$jd");
		$judul = explode("-",$jd_decrypt);
		$order_id=$judul[1];
		
		$parameter="order_id=".$order_id." AND detail_is_delete=0 AND ";
		
		$res_tot = $this->mm->count_all_data("v_tbl_detail", NULL, $parameter."produk");
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		if( !empty($requestData['search']['value']) )
		{
			$where = $parameter."(produk_nama LIKE '%".$requestData['search']['value']."%')";	
			$res = $this->mm->get_search_data("v_tbl_detail", $where, "produk", $order_by, $limit, $offset);
			
			$res_filtered_tot = $this->mm->count_all_data("v_tbl_detail", $where, "produk");
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_all_data("v_tbl_detail", $parameter."produk", $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}
		
		$data = array();if(!empty($res)){
		foreach($res as $row)
		{
			$random = rand();
			$id = base64_encode($random."-".$row->detail_id);
			$produk = base64_encode($random."-".$row->produk_id);
			$edit = base64_encode($random."-edit");
			$delete = base64_encode($random."-delete");
			
			$produk_nama = $row->produk_nama;
			$jml = strlen($produk_nama);
			if($jml>70){
				$produk_tanya = substr($produk_nama,0,70)."...";
			}else{
				$produk_tanya = $produk_nama;
			}
			
			$nestedData = array();
			$nestedData[] = "<input type=\"hidden\" name=\"hdd_detail_id[]\" id=\"hdd_detail_id\" readonly=\"readonly\" value=\"".$row->detail_id."\">".$offset=$offset+1;
			$nestedData[] = strtoupper($produk_tanya);
			$nestedData[] = number_format($row->detail_harga,0,',','.');
			$nestedData[] = "<input type=\"text\" name=\"txt_jumlah[]\" id=\"txt_jumlah\" size='2' value=\"".$row->detail_jumlah."\">";
			$nestedData[] = "
			<a href='javascript:void(0)' oncontextmenu=\"viewklik_function('".$edit."','".$produk."'); return false;\" onClick=view_function('".$edit."','".$produk."'); class='btn btn-sm btn-info' title=\"Lihat Produk\"><i class='fa fa-search'></i></a> 
			<a href='javascript:void(0)' oncontextmenu=\"delklik_function('".$delete."','".$id."'); return false;\" onClick=delete_function('".$delete."','".$id."');  class='btn btn-sm btn-danger' title=\"Batal Produk\"><i class='fa fa-remove'></i></a>
			";
			
			$data[] = $nestedData;}
		}
		
		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval($tot_data),
			"recordsFiltered" => intval($tot_filtered),
			"data"            => $data
		);
		echo json_encode($json_data);
	}
	
	function form_pilih()
	{
		if($this->input->get("m") && $this->input->get("a"))
		{
			$get_a = $this->input->get("a");
			$dec_a = base64_decode("$get_a");
			$a = explode("-",$dec_a);
			$alert = $a[1];
			
			$get_m = $this->input->get("m");
			$dec_m = base64_decode("$get_m");
			$m = explode("-",$dec_m);
			$message = $m[1];
			
			$data["alert"] = $alert;
			$data["message"] = $message;
			
			if(is_string($alert)==FALSE || ($alert!='success' && $alert!='warning') || is_string($message)==FALSE){
				redirect(site_url("auth/warning"));
			}
		}
		
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$judul = explode("-",$id_decrypt);
		$id_judul = $judul[1];
		
		if($id_judul=="" || is_numeric($id_judul)==FALSE){ 
			redirect(site_url("auth/warning")); 
		}
		
		$data["qry_spr"]   = $this->mm->get_all_data("m_status_produk","spr","spr_id","100","0");
		$data["qry_sdr"]   = $this->mm->get_all_data("m_status_order","sdr","sdr_id","100","0");	
		$data["qry_judul"] = $this->mm->get_data_by_id("order_id",$id_judul,"v_tbl_order","order");
		
		$data["page"] = "view_user/opportunity/form_pilih";
		$data["title"] = "pilih produk";
		$this->load->view('template/template', $data);
	}

	function form()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_keterangan', 'Judul Peluang', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			$m = $this->input->get("m");
			$m_decrypt = base64_decode("$m");
			$method = explode("-",$m_decrypt);
			$mode_ket = $method[1];
		
			if($mode_ket === "edit")
			{
				$id = $this->input->get("id");
				$id_decrypt = base64_decode("$id");
				$judul = explode("-",$id_decrypt);
				$id_judul = $judul[1];
				
				if($id_judul=="" || is_numeric($id_judul)==FALSE){ redirect(site_url("auth/warning")); }
				$data["qry_judul"] = $this->mm->get_data_by_id("order_id",$id_judul,"tbl_order","order");
			}
			
			$member_id = $this->session->userdata("memberId");
			$parameter = "member_update_by=".$member_id." AND member_id<>".$member_id." AND member";
			
			$data["qry_spr"]    = $this->mm->get_all_data("m_status_produk","spr","spr_id","100","0");
			$data["qry_sdr"]    = $this->mm->get_all_data("m_status_order","sdr","sdr_id","100","0");	
			$data["qry_order"]  = $this->mm->get_all_data("tbl_order","order","order_id","100","0");
			$data["qry_member"] = $this->mm->get_all_data("tbl_member",$parameter,"member_nama","1000","0");
		
			$data["page"] = "view_user/opportunity/form";
			$data["title"] = "Peluang Penjualan & Kerjasama";
			$this->load->view('template/template', $data);
		}
		else
		{
			if($this->input->post("btn_simpan") === "btn_simpan")
			{
				$member_id = $this->session->userdata("memberId");
				
				$field[] = "order_biaya1,";
				$field[] = "order_biaya2,";
				$field[] = "order_biaya3,";

				$field[] = "order_judul,";
				$field[] = "order_member,";
				$field[] = "order_status,";
				$field[] = "order_tanggal,";
				$field[] = "order_keterangan,";
				
				$field[] = "order_is_delete,";
				$field[] = "order_update_by,";
				$field[] = "order_update_date";
				
				$data[] = "'".str_replace(".","",$this->input->post("order_biaya1"))."',";
				$data[] = "'".str_replace(".","",$this->input->post("order_biaya2"))."',";
				$data[] = "'".str_replace(".","",$this->input->post("order_biaya3"))."',";
				
				$data[] = "'".$this->input->post("txt_judul")."',";
				$data[] = "'".$this->input->post("opt_member")."',";
				$data[] = "'".$this->input->post("opt_status")."',";
				$data[] = "'".$this->input->post("txt_tanggal")."',";
				$data[] = "'".$this->input->post("txt_keterangan")."',";

				$data[] = "0,";
				$data[] = "".$member_id.",";
				$data[] = "'".date("Y-m-d H:i:s")."'";
				
				$order_ket=$this->input->post("txt_keterangan");
				$cekdata="order_keterangan like '".$order_ket."'";
				$cek_file = $this->mm->check_duplicate("tbl_order", "order" ,$cekdata);
				
				if($cek_file->jml === "0")
				{
					$insert_data = $this->mm->insert_data("tbl_order", $field, $data);
					if($insert_data === 1)
					{
						$rand = rand();
						$msg = base64_encode($rand."-Data berhasil ditambah");
						$alert = base64_encode($rand."-success");
						redirect(site_url("class_judul/index?m=".$msg."&a=".$alert));
					}
					else
					{
						$msg = base64_encode($rand."-Data tidak berhasil ditambah");
						$alert = base64_encode($rand."-danger");
						redirect(site_url("class_judul/index?m=".$msg."&a=".$alert));
					}
					
				}else{
					?>
					<script type="text/javascript">
					alert("Judul Ujian Sudah Ada!");
					window.history.back();
					</script>
					<?php
				}
				
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{	
				$member_id  = $this->session->userdata("memberId");
				$order_id = $this->input->post("txt_order_id");

				$data["order_biaya1"]  = "'".str_replace(".","",$this->input->post("order_biaya1"))."',";
				$data["order_biaya2"]  = "'".str_replace(".","",$this->input->post("order_biaya2"))."',";
				$data["order_biaya3"]  = "'".str_replace(".","",$this->input->post("order_biaya3"))."',";

				$data["order_judul"]       = "'".$this->input->post("txt_judul")."',";
				$data["order_member"]      = "'".$this->input->post("opt_member")."',";
				$data["order_status"]      = "'".$this->input->post("opt_status")."',";
				$data["order_tanggal"]     = "'".$this->input->post("txt_tanggal")."',";
				$data["order_keterangan"]  = "'".$this->input->post("txt_keterangan")."',";

				$data["order_update_by"]   = "".$member_id.",";
				$data["order_update_date"] = "'".date("Y-m-d H:i:s")."'";
				
				$update_data = $this->mm->update_data("tbl_order", "order_id", $order_id, $data);
				if($update_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil diubah");
					$alert = base64_encode($rand."-success");
					redirect(site_url("class_judul/index?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil diubah");
					$alert = base64_encode($rand."-danger");
					redirect(site_url("class_judul/index?m=".$msg."&a=".$alert));
				}
			}
			else
			{
				redirect(site_url("class_judul"));
			}
		}
	}
	
	function delete_ujian()
	{
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$id_produk = explode("-",$id_decrypt);
		
		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);
		
		if($id && $method[1] === "delete")
		{
			$this->mm->delete_data("tbl_order","order", "order_id",$id_produk[1]);
			
			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(site_url("class_judul/index?m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(site_url("class_judul"));
		}
	}
	
	function submit()
	{
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$produk = explode("-",$id_decrypt);
		$produk_id = $produk[1];
		
		$jd = $this->input->get("ejd");
		$jd_decrypt = base64_decode("$jd");
		$judul = explode("-",$jd_decrypt);
		$order_id = $judul[1]; 
		
		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);
		$metode = $method[1];
		
		$member_id = $this->session->userdata("memberId");
		$get_harga = $this->mm->get_data_by_id("produk_id",$produk_id,"tbl_produk","produk");
		$harga = $get_harga->produk_harga;

		if($id && $metode == "edit")
		{
			$field[] = "detail_order,";
			$field[] = "detail_produk,";
			$field[] = "detail_harga,";
			$field[] = "detail_jumlah,";

			$field[] = "detail_is_delete,";
			$field[] = "detail_update_by,";
			$field[] = "detail_update_date";
			
			$data[] = $order_id.",";
			$data[] = $produk_id.",";
			$data[] = $harga.",";
			$data[] = "1,";
			
			$data[] = "0,";
			$data[] = "".$member_id.",";
			$data[] = "'".date("Y-m-d H:i:s")."'";
			
			$insert_data = $this->mm->insert_data("tbl_detail", $field, $data);	
			if($insert_data === 1)
			{
				$rand = rand();
				$msg = base64_encode($rand."-Data berhasil disimpan");
				$alert = base64_encode($rand."-success");
				$thn = $this->input->get("th");
				redirect(site_url("class_judul/form_pilih?id=".$jd."&th=".$thn."&m=".$msg."&a=".$alert));
			}
			else
			{
				$msg = base64_encode($rand."-Data tidak berhasil disimpan");
				$alert = base64_encode($rand."-danger");
				$thn = $this->input->get("th");
				redirect(site_url("class_judul/form_pilih?id=".$jd."&th=".$thn."&m=".$msg."&a=".$alert));
			}
			
		}else{
			redirect(site_url("class_judul"));
		}
	}
	
	function batal_produk()
	{
		$thn = $this->input->get("th");
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$detail = explode("-",$id_decrypt);
		$detail_id = $detail[1];
		
		$jd = $this->input->get("ejd");
		$jd_decrypt = base64_decode("$jd");
		$judul = explode("-",$jd_decrypt);
		$order_id = $judul[1]; 
		
		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);
		$metode = $method[1];
			
		if($id && $metode === "delete")
		{
			$this->mm->delete_data("tbl_detail","detail", "detail_id",$detail_id);
			
			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(site_url("class_judul/form_pilih?id=".$jd."&th=".$thn."&m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(site_url("class_judul"));
		}
	}
	
	function update_ajax()
	{		
		$item = $this->input->get("hdd_detail_id"); 
		$jumlah = $this->input->get("txt_jumlah"); 

		for($i=0;$i<count($item);$i++)
		{
			$member_id = $this->session->userdata("memberId");
			$waktu=date("Y-m-d H:i:s");

			$item_id=$item[$i];
			$item_jumlah=$jumlah[$i];
			
			$this->mm->update_item($item_id,$item_jumlah,$member_id,$waktu);
		}
	}
	
	function refresh_dataku()
	{
        $this->session->unset_userdata('session_parent');
		redirect(site_url("class_judul"));
	}
	
	function tampil_dataku()
	{
		$this->session->unset_userdata('session_parent');
		
		$id = $this->input->get('id');
        $data = array('session_parent' => $id);
		$this->session->set_userdata($data);	
		
		redirect(site_url("class_judul"));
	}
	
	function anti_xss($string)
	{
		$filter=stripslashes(strip_tags(htmlspecialchars(trim($string),ENT_QUOTES)));
		return $filter;
	}
	
    private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedInUSR') OR $this->session->userdata('isLoggedInUSR') === FALSE)
        {
            redirect("auth");
        }
    }
}