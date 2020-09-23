<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Class_user extends CI_Controller
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
		
		$data["page"]  = "view_user/account/home";
        $data["title"] = "Pelanggan & Calon Pelanggan";
		$this->load->view('template/template', $data);
	}
	
	function member_ajax()
	{
		$requestData= $_REQUEST;
		$columns = array( 
			0 => '',
			1 => 'member_id', 
			2 => 'member_nama',
			3 => 'member_kelamin',
			4 => 'member_status',
			5 => 'member_stage',
			6 => 'member_phone',
			7 => 'member_alamat',
			8 => 'member_email',
		);
		
		$member_id = $this->session->userdata("memberId");
		$myparam = "member_update_by=".$member_id." and member_id<>".$member_id;
		
		$res_tot  = $this->mm->count_all_data("v_tbl_member", $myparam ,"member");
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		if( !empty($requestData['search']['value']) )
		{
			$where = $myparam." AND (member_nama LIKE '%".$requestData['search']['value']."%')";
			$res = $this->mm->get_search_data("v_tbl_member", $where, "member", $order_by, $limit, $offset);
			
			$res_filtered_tot = $this->mm->count_all_data("v_tbl_member", $where, "member");
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_search_data("v_tbl_member", $myparam , "member", $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}
		
		$data = array();if(!empty($res)){
		foreach($res as $row)
		{
			$random = rand();
			$id = base64_encode($random."-".$row->member_id);
			$edit = base64_encode($random."-edit");
			$delete = base64_encode($random."-delete");
			
			if($row->member_kelamin=="1"){ $member_kelamin="Laki-laki";}
			if($row->member_kelamin=="2"){ $member_kelamin="Perempuan";}
			
			if($row->member_stage=="1"){ $member_stage="Cold";}
			if($row->member_stage=="2"){ $member_stage="Warm";}
			if($row->member_stage=="3"){ $member_stage="Hot";}
			
			$nestedData = array();
			$nestedData[] = "
			<a href='javascript:void(0)' onClick=view_function('".$edit."','".$id."');  class='btn btn-sm btn-success' title=\"View Opportunity\"><i class='fa fa-send'></i></a>
			<a href='javascript:void(0)' onClick=edit_function('".$edit."','".$id."');  class='btn btn-sm btn-info' title=\"Edit\"><i class='fa fa-check'></i></a>
			<a href='javascript:void(0)' onClick=del_function('".$delete."','".$id."'); class='btn btn-sm btn-danger' title=\"Delete\"><i class='fa fa-trash'></i></a>
			";
			$nestedData[] = $row->member_id;
			$nestedData[] = "<a href='javascript:;'>".strtoupper($row->member_nama)."</a>";
			$nestedData[] = strtoupper($member_kelamin);
			$nestedData[] = strtoupper($row->ssr_ket);
			$nestedData[] = strtoupper($member_stage);
			$nestedData[] = "<font color='blues'>".strtolower($row->member_phone)."</font>";
			$nestedData[] = "<font color='blues'>".strtoupper($row->member_alamat)."</font>";
			$nestedData[] = "<font color='blues'>".strtolower($row->member_email)."</font>";
			
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
	
	function view_ajax()
	{
		$requestData= $_REQUEST;
	
		$columns = array( 
			0 => 'order_id',
			1 => 'order_keterangan',
			2 => 'order_biaya2',
			3 => 'order_member',
			4 => 'order_biaya1',
			5 => '',
			6 => 'order_tanggal',
		);
		
		$get_id = $this->input->get('id');
		$dec_n = base64_decode($get_id);
		$n = explode("-",$dec_n);
		$account_id = $n[1];
		
		$member_id = $this->session->userdata("memberId");
		$myparam = "order_update_by=".$member_id." AND order_member=".$account_id;
		
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

			$cekdata="detail_order=".$row->order_id."";
			$cek_produk=$this->mm->check_duplicate("tbl_detail","detail",$cekdata);
			$jumlah=$cek_produk->jml;
			
			$nestedData = array();
			$nestedData[] = $row->order_id;
			$nestedData[] = "<a href='javascript:;' title='Klik untuk detail info' onClick=order_function('".$edit."','".$id."');>".strtoupper($row->order_keterangan)."</a>";
			$nestedData[] = "<a href='javascript:;' title='Klik untuk detail info' onClick=user_function('".$edit."','".$us."');>".strtoupper($row->member_nama)."</a>";
			$nestedData[] = $order_status;
			$nestedData[] = $jumlah." Produk";
			$nestedData[] = ucwords($row->order_tanggal);
			
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
	
	function delete_member()
	{
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$member = explode("-",$id_decrypt);
		$member_id = $member[1];
		
		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);
		
		if($id && $method[1] === "delete")
		{
			$this->mm->delete_data("tbl_member","member", "member_id", $member_id);
			
			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(site_url("class_user?m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(site_url("class_user"));
		}
	}
	
	function form_view()
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
		$member = explode("-",$id_decrypt);
		$member_id = $member[1];
		
		if($member_id=="" || is_numeric($member_id)==FALSE){ 
			redirect(site_url("auth/warning")); 
		}
		
		$data["qry_member"] = $this->mm->get_data_by_id("member_id",$member_id,"tbl_member","member");
		
		$data["page"] = "view_user/account/home_view";
		$data["title"] = "View Opportunity";
		$this->load->view('template/template', $data);
	}
	
	function form()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('member_nama', 'Nama member', 'required');
		
		if ($this->form_validation->run() === FALSE)
		{
			$m = $this->input->get("m");
			$m_decrypt = base64_decode($m);
			$method = explode("-",$m_decrypt);
			$mode_ket = $method[1];

			if($mode_ket === "edit")
			{
				$id = $this->input->get("id");
				$id_decrypt = base64_decode($id);
				$member = explode("-",$id_decrypt);
				$member_id = $member[1];
				
				if($member_id=="" || is_numeric($member_id)==FALSE){ redirect(site_url("auth/warning"));}
				$data["qry_member"] = $this->mm->get_data_by_id("member_id",$member_id,"tbl_member","member");
			}
			
			$data["page"] = "view_user/account/form";
			$data["title"] = "Pelanggan & Calon Pelanggan";
			$this->load->view('template/template', $data);
		}
		else
		{
			if($this->input->post("btn_simpan") === "btn_simpan")
			{
				$member_id = $this->session->userdata("memberId");
				
				$field[] = "member_nama,";
				$field[] = "member_email,";
				$field[] = "member_kelamin,";
				$field[] = "member_status,";
				$field[] = "member_phone,";
				$field[] = "member_alamat,";
				$field[] = "member_stage,";
		
				$field[] = "member_is_delete,";
				$field[] = "member_update_by,";
				$field[] = "member_update_date";
				
				$data[] = "'".$this->anti_xss($member_nama=$this->input->post("member_nama"))."',";
				$data[] = "'".$this->anti_xss($this->input->post("member_email"))."',";
				$data[] = "'".$this->input->post("member_kelamin")."',";
				$data[] = "'".$this->input->post("member_status")."',";
				$data[] = "'".$this->input->post("member_phone")."',";
				$data[] = "'".$this->input->post("member_alamat")."',";
				$data[] = "'".$this->input->post("member_stage")."',";
				
				$data[] = "0,";
				$data[] = "".$member_id.",";
				$data[] = "'".date("Y-m-d H:i:s")."'";
				
				$cekdata="member_nama LIKE '".$member_nama."'";
				$cek_member = $this->mm->check_duplicate("tbl_member", "member" ,$cekdata);
				
				if($cek_member->jml === "0")
				{
					$insert_data = $this->mm->insert_data("tbl_member", $field, $data);
					if($insert_data === 1)
					{
						$rand = rand();
						$msg = base64_encode($rand."-Data berhasil ditambah");
						$alert = base64_encode($rand."-success");
						redirect(site_url("class_user/index?m=".$msg."&a=".$alert));
					}
					else
					{
						$msg = base64_encode($rand."-Data tidak berhasil ditambah");
						$alert = base64_encode($rand."-danger");
						redirect(site_url("class_user/index?m=".$msg."&a=".$alert));
					}
				}
				else
				{
					?>
					<script type="text/javascript">
					alert("Nama user sudah ada");
					window.history.back();
					</script>
					<?php
				}
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{
				$member_id  = $this->session->userdata("memberId");
				$member_hdd = "'".$this->input->post("hdd_member_id")."'";
				
				$data["member_nama"]     = "'".$this->anti_xss($this->input->post("member_nama"))."',";
				$data["member_email"]    = "'".$this->anti_xss($this->input->post("member_email"))."',";
				$data["member_kelamin"]  = "'".$this->anti_xss($this->input->post("member_kelamin"))."',";
				$data["member_status"]   = "'".$this->anti_xss($this->input->post("member_status"))."',";
				$data["member_phone"]    = "'".$this->anti_xss($this->input->post("member_phone"))."',";
				$data["member_stage"]    = "'".$this->anti_xss($this->input->post("member_stage"))."',";
				$data["member_alamat"]   = "'".$this->anti_xss($this->input->post("member_alamat"))."',";

				$data["member_update_by"]   = "".$member_id.",";
				$data["member_update_date"] = "'".date("Y-m-d H:i:s")."'";
				
				$update_data = $this->mm->update_data("tbl_member", "member_id", $member_hdd, $data);
				if($update_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil diubah");
					$alert = base64_encode($rand."-success");
					redirect(site_url("class_user?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil diubah");
					$alert = base64_encode($rand."-danger");
					redirect(site_url("class_user?m=".$msg."&a=".$alert));
				}	
			}
			else
			{
				redirect(site_url("class_user"));
			}
		}
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