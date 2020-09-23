<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Class_item extends CI_Controller
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
		
		$data["page"]  = "view_user/product/home";
        $data["title"] = "Produk Barang & Jasa";
		$this->load->view('template/template', $data);
	}
	
	function produk_ajax()
	{
		$requestData= $_REQUEST;
	
		$columns = array( 
			0 => '',
			1 => 'produk_id', 
			2 => 'produk_nama',
			3 => 'produk_harga',
			4 => 'spr_ket',
			5 => 'produk_keterangan',
		);
		
		$member_id = $this->session->userdata("memberId");
		$myparam = "produk_update_by=".$member_id;
		
		$res_tot  = $this->mm->count_all_data("v_tbl_produk",$myparam,"produk");
		$tot_data = $res_tot->jml;

		$order_by = $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir'];
		$offset = $requestData['start'];
		$limit = $requestData['length'];

		if( !empty($requestData['search']['value']) )
		{
			$where  = $myparam." AND (produk_nama LIKE '%".$requestData['search']['value']."%'";
			$where .= " OR spr_ket LIKE '%".$requestData['search']['value']."%')";
			
			$res = $this->mm->get_search_data("v_tbl_produk", $where,"produk",$order_by,$limit,$offset);
			
			$res_filtered_tot = $this->mm->count_all_data("v_tbl_produk",$where,"produk");
			$tot_filtered = $res_filtered_tot->jml;
		}
		else
		{
			$res = $this->mm->get_search_data("v_tbl_produk", $myparam, "produk" , $order_by, $limit, $offset);
			$tot_filtered = $tot_data;
		}
		
		$data = array();if(!empty($res)){
		foreach($res as $row)
		{
			$random = rand();
			$id = base64_encode($random."-".$row->produk_id);
			$edit = base64_encode($random."-edit");
			$delete = base64_encode($random."-delete");
			
			$pertanyaan = $row->produk_nama;
			$jml = strlen($pertanyaan);
			$jumlah = intval($jml);
	
			if($jumlah>70){
				$produk_tanya = substr($pertanyaan,0,70)."...";
			}else{
				$produk_tanya = $pertanyaan;
			}
			
			if ($row->produk_status == 1){ $status_produk="<span class='label label-success'>TERSEDIA</span>";}
			if ($row->produk_status == 2){ $status_produk="<span class='label label-danger'>TIDAK TERSEDIA</span>";}
			
			$nestedData = array();
			$nestedData[] = "
			<a href='javascript:void(0)' oncontextmenu=\"editklik_function('".$edit."','".$id."'); return false;\" onClick=edit_function('".$edit."','".$id."');  class='btn btn-sm btn-info' title=\"Edit\"><i class='fa fa-check'></i></a>
			<a href='javascript:void(0)' oncontextmenu=\"delklik_function('".$delete."','".$id."'); return false;\" onClick=del_function('".$delete."','".$id."'); class='btn btn-sm btn-danger' title=\"Delete\"><i class='fa fa-trash'></i></a>
			";
			$nestedData[] = $row->produk_id;
			$nestedData[] = "<a href='javascript:;'>".strtoupper($this->anti_xss($produk_tanya))."</a>";
			$nestedData[] = "<font color='blues'>".number_format($row->produk_harga,0,',','.')."</font>";
			$nestedData[] = $status_produk;
			$nestedData[] = "<i>".ucwords($row->produk_keterangan)."</i>";
	
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
	
	function delete_produk()
	{
		$id = $this->input->get("id");
		$id_decrypt = base64_decode("$id");
		$id_produk = explode("-",$id_decrypt);
		
		$m = $this->input->get("m");
		$m_decrypt = base64_decode("$m");
		$method = explode("-",$m_decrypt);
		
		if($id && $method[1] === "delete")
		{
			$this->mm->delete_data("tbl_produk","produk", "produk_id",$id_produk[1]);
			
			$rand = rand();
			$msg = base64_encode($rand."-Data berhasil dihapus");
			$alert = base64_encode($rand."-success");
			redirect(site_url("class_item/index?m=".$msg."&a=".$alert));
		}
		else
		{
			redirect(site_url("class_item"));
		}
	}
	
	function form()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_nama', 'Nama Produk', 'required');
		
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
				$produk = explode("-",$id_decrypt);
				$id_produk = $produk[1];
				
				if($id_produk=="" || is_numeric($id_produk)==FALSE){ redirect(site_url("auth/warning")); }
				$data["qry_produk"] = $this->mm->get_data_by_id("produk_id",$id_produk,"tbl_produk","produk");
			}
			
			$data["qry_spr"]   = $this->mm->get_all_data("m_status_produk","spr","spr_id","100","0");
			$data["qry_sdr"] = $this->mm->get_all_data("m_status_order","sdr","sdr_id","100","0");
			
			$data["page"] = "view_user/product/form";
			$data["title"] = "Produk Barang & Jasa";
			$this->load->view('template/template', $data);
		}
		else
		{
			if($this->input->post("btn_simpan") === "btn_simpan")
			{
				$member_id         = $this->session->userdata("memberId");
				$produk_status     = $this->input->post("opt_status");
				$produk_nama       = $this->input->post("txt_nama");
				$produk_harga  	   = $this->input->post("txt_harga");
				$produk_keterangan = $this->input->post("txt_keterangan");

				$field[] = "produk_nama,";
				$field[] = "produk_harga,";
				$field[] = "produk_keterangan,";
				$field[] = "produk_status,";

				$field[] = "produk_is_delete,";
				$field[] = "produk_update_by,";
				$field[] = "produk_update_date";
			
				$data[] = "'".$produk_nama."',";
				$data[] = "'".str_replace(".","",$produk_harga)."',";
				$data[] = "'".$produk_keterangan."',";
				$data[] = "'".$produk_status."',";

				$data[] = "0,";
				$data[] = "".$member_id.",";
				$data[] = "'".date("Y-m-d H:i:s")."'";
	
				$insert_data = $this->mm->insert_data("tbl_produk", $field, $data);
				if($insert_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil ditambah");
					$alert = base64_encode($rand."-success");
					redirect(site_url("class_item/index?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil ditambah");
					$alert = base64_encode($rand."-danger");
					redirect(site_url("class_item/index?m=".$msg."&a=".$alert));
				}
			}
			elseif ($this->input->post("btn_simpan") === "btn_ubah")
			{	
				$member_id = $this->session->userdata("memberId");
				$produk_id = $this->input->post("txt_produk_id");
				$produk_status   = $this->input->post("opt_status");
				$rand = rand();

				$data["produk_status"]      = "'".$produk_status."',";
				$data["produk_nama"] 	    = "'".$this->input->post("txt_nama")."',";
				$data["produk_harga"]       = "'".str_replace(".","",$this->input->post("txt_harga"))."',";
				$data["produk_keterangan"]  = "'".$this->input->post("txt_keterangan")."',";

				$data["produk_update_by"]   = "".$member_id.",";
				$data["produk_update_date"] = "'".date("Y-m-d H:i:s")."'";

				$update_data = $this->mm->update_data("tbl_produk", "produk_id", $produk_id, $data);
				if($update_data === 1)
				{
					$rand = rand();
					$msg = base64_encode($rand."-Data berhasil diubah");
					$alert = base64_encode($rand."-success");
					$eproduk = $this->input->post("hdd_produk_id");
					redirect(site_url("class_item/index?m=".$msg."&a=".$alert));
				}
				else
				{
					$msg = base64_encode($rand."-Data tidak berhasil diubah");
					$alert = base64_encode($rand."-danger");
					redirect(site_url("class_item/index?m=".$msg."&a=".$alert));
				}
			}
			else
			{
				redirect(site_url("class_item"));
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