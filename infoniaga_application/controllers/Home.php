<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/*
	 * @autor KangAgus <sumarna.agus@gmail.com>
     */
	 
	function __construct()
	{
		parent::__construct();
		$this->cek_login();
		$this->output->enable_profiler(FALSE);
		date_default_timezone_set("Asia/Jakarta");
		$this->load->model("master_model", "mm");
	}
	
	public function index()
	{
		$b = time();
		$hour = date("G",$b);
		if ($hour>=0 && $hour<=11){
			$salam="Selamat Pagi";
		}elseif ($hour >=12 && $hour<=14){
			$salam="Selamat Siang";
		}elseif ($hour >=15 && $hour<=17){
			$salam="Selamat Sore";
		}elseif ($hour >=17 && $hour<=18){
			$salam="Selamat Petang";
		}elseif ($hour >=19 && $hour<=23){
			$salam="Selamat Malam";
		}
		
		$user_id  = $this->session->userdata("memberId");
		$cekdata1 = "member_update_by=".$user_id." AND member_status=2 AND member_id<>".$user_id;
		$cekdata2 = "member_update_by=".$user_id." AND member_status=1 AND member_id<>".$user_id;
		$cekdata3 = "produk_update_by=".$user_id;
		$cekdata4 = "order_update_by=".$user_id;
		
		$qry_lead        = $this->mm->check_duplicate("tbl_member","member",$cekdata1);
		$qry_account 	 = $this->mm->check_duplicate("tbl_member","member",$cekdata2);
		$qry_product 	 = $this->mm->check_duplicate("tbl_produk","produk",$cekdata3);
		$qry_opportunity = $this->mm->check_duplicate("tbl_order","order",$cekdata4);
		
		$jml_lead 		 = $qry_lead->jml;
		$jml_account 	 = $qry_account->jml;
		$jml_product 	 = $qry_product->jml;
		$jml_opportunity = $qry_opportunity->jml;

		$data["jml_lead"] 	 	 = $jml_lead;
		$data["jml_account"] 	 = $jml_account;
		$data["jml_product"] 	 = $jml_product;
		$data["jml_opportunity"] = $jml_opportunity;
		
		$data["page"]  = "home";
		$data["title"] = "Assalamu'alaikum, ".$salam;
		$this->load->view('template/template',$data);
	}
	
	private function cek_login()
    {
        if( ! $this->session->userdata('isLoggedInUSR') OR $this->session->userdata('isLoggedInUSR') === FALSE)
        {
            redirect("auth");
        }
    }
}