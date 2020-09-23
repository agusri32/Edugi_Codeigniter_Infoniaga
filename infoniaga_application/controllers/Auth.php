<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller
{
	/*
	 * @autor KangAgus <sumarna.agus@gmail.com>
     */
	
	function __construct()
	{
		parent::__construct();
		$this->output->enable_profiler(FALSE);
		$this->load->model("auth_model","am");
		$this->load->model("master_model", "mm");
		date_default_timezone_set("Asia/Jakarta");
	}

    function index()
    {
		if($this->session->userdata('isLoggedInUSR') === TRUE)
        {
        	redirect('home');
        }else{
            $this->load->view('view_login/home_login');
            //$this->load->view('view_login/home_close');
        }
    }
	
    function validate_credential()
    {
		if($this->input->post('btn_login') === 'btn_login')
		{	
			$find = array("//","\\");
			$username=str_replace($find,"'",$this->input->post('txt_member_name'));
			$password=str_replace($find,"'",$this->input->post('txt_member_password'));
			
			$filter_membername=$this->anti_xss($username,TRUE);
			$filter_password=$this->anti_xss($password,TRUE);
			
			$cek_member = $this->am->validate($filter_membername,$filter_password,"1=1");
			if($cek_member)
			{
				$u_agent=$_SERVER['HTTP_USER_AGENT'];
				$is_mobile = preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge|maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm(os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$u_agent);
				if($is_mobile){ $device='Mobile'; }else{ $device='Desktop'; }
				
				$parameter="'".$username."' AND member_password=md5('".$password."')";
				$cari_data = $this->mm->get_data_by_id("member_username",$parameter,"tbl_member","member");
				
				$data = array(		
					'userDevice'   => $device,
					'userName'     => $username,
					'memberId'     => $cek_member->member_id,
					'memberNama'   => $cek_member->member_nama,
					'memberEmail'  => $cek_member->member_email,
					'memberStatus' => $cek_member->member_status,
					'waktuLogin'   => $waktu_masuk=date('Y-m-d H:i:s'),
					'isLoggedInUSR'  => TRUE
				);
				
				$this->session->set_userdata($data);
				redirect("home");
				
			}else{
				$data['error'] = "Username atau Password salah";
				$this->load->view('view_login/home_login');
			}
		}
		else
		{
			$data['error'] = "Anda harus login terlebih dahulu";
			$this->load->view('view_login/home_login');
		}
    }
	
	function update_password()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txt_old_password', 'Password Lama', 'trim|required');
		$this->form_validation->set_rules('txt_new_password', 'Password Baru', 'trim|required|matches[txt_confirm_password]');
		$this->form_validation->set_rules('txt_confirm_password', 'Password Confirmation', 'required');

		if ($this->form_validation->run() == FALSE)
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
			
			$data["title"] = "Password";
			$data["page"] = "view_login/form_password";
			$this->load->view("template/template", $data);
		}
		else
		{
			$data["old_password"] = $this->input->post("txt_old_password");
			$data["new_password"] = $this->input->post("txt_new_password");
			$data["confirm_password"] = $this->input->post("txt_confirm_password");
			
			$cek_old_password = $this->am->check_password($data["old_password"]);

			if($cek_old_password->jml === "1")
			{
				$update = $this->am->update_password($data);

				$data["title"] = "Ubah Password";
				$data["status"] = "success";
				$data["page"] = "view_login/form_password";
				$data["message"] = "Password Berhasil diubah. Silahkan Logout dan Login kembali";
				$this->load->view("template/template", $data);
			}
			else
			{
				$data["title"] = "Ubah Password";
				$data["status"] = "danger";
				$data["page"] = "view_login/form_password";
				$data["message"] = "Password Lama tidak sama";
				$this->load->view("template/template", $data);
			}
		}
	}
	
	function update_biodata()
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
			
			if(is_string($alert)==FALSE || ($alert!='success' && $alert!='danger') || is_string($message)==FALSE){
				redirect(site_url("auth/warning"));
			}
		}
		
		$id_member = $this->session->userdata("memberId");;
		$data["qry_member"] = $this->mm->get_data_by_id("member_id",$id_member,"tbl_member","member");
		
		$data["page"] = "view_login/form_biodata";
        $data["title"] = "Melengkapi Biodata dengan benar";
		$this->load->view('template/template', $data);
	}
	
	function logout()
	{
		if($this->session->userdata('isLoggedInUSR') === TRUE)
        {
			$user_id=$this->session->userdata('memberId');
			$waktu_masuk=$this->session->userdata('waktuLogin');
			$waktu_keluar=date('Y-m-d H:i:s');
			
			if($waktu_masuk=="" || $waktu_keluar=="" || $user_id==""){
				$this->session->sess_destroy();
				redirect("auth");
			}else{
				$this->session->sess_destroy();
				redirect("auth");
			}
			
        }else{
			$this->session->sess_destroy();
			redirect("auth");
		}
	}
	
	function update()
	{
		$tombol_login=$this->input->post('btn_simpan');
		if(isset($tombol_login))
		{
			$user_id   = $this->session->userdata("memberId");
			$member_id = "'".$this->input->post("hdd_member_id")."'";
			
			$data["member_nama"] 	 = "'".$this->anti_xss($this->input->post("member_nama"))."',";
			$data["member_email"]    = "'".$this->anti_xss($this->input->post("member_email"))."',";
			$data["member_username"] = "'".$this->anti_xss($this->input->post("member_email"))."',";
			$data["member_kelamin"]  = "'".$this->anti_xss($this->input->post("member_kelamin"))."',";
			$data["member_alamat"]   = "'".$this->anti_xss($this->input->post("member_alamat"))."',";
			$data["member_phone"]	 = "'".$this->anti_xss($this->input->post("member_phone"))."',";
			
			$data["member_update_by"] = "".$user_id.",";
			$data["member_update_date"] = "'".date("Y-m-d H:i:s")."'";
			
			$update_data = $this->mm->update_data("tbl_member", "member_id", $member_id, $data);
			if($update_data === 1)
			{
				$rand  = rand();
				$msg   = base64_encode($rand."-Data berhasil diubah.");
				$alert = base64_encode($rand."-success");
				redirect(site_url("auth/update_biodata?m=".$msg."&a=".$alert));
			}
			else
			{
				$rand  = rand();
				$msg   = base64_encode($rand."-Data tidak berhasil diubah");
				$alert = base64_encode($rand."-danger");
				redirect(site_url("auth/update_biodata?m=".$msg."&a=".$alert));
			}	
		}
		else
		{
			redirect(site_url("auth/update_biodata"));
		}
	}
	
	function save()
	{
		$tombol_login=$this->input->post('btn_simpan');
		if(isset($tombol_login))
		{				
			$field[] = "member_nama,";
			$field[] = "member_email,";
			$field[] = "member_kelamin,";
			$field[] = "member_username,";
			$field[] = "member_password,";
	
			$field[] = "member_is_delete,";
			$field[] = "member_update_by,";
			$field[] = "member_update_date";
			
			$namalengkap = $this->input->post("txt_nama");
			$alamatemail = $this->input->post("txt_member_name");
			$username 	 = $this->input->post("txt_member_name");
			$password 	 = $this->input->post("txt_member_password");
			$kelamin  	 = $this->input->post("member_kelamin");
			$passwordku  = md5($password);
			
			if($this->cek_kata($namalengkap) || $this->cek_kata($alamatemail) || $this->cek_kata($username))
			{
				?>
				<script type="text/javascript">
				alert("Tidak boleh menggunakan kata tes/test");
				window.history.back();
				</script>
				<?php
			}
			else
			{
				$data[] = "'".$this->anti_xss($namalengkap)."',";
				$data[] = "'".$this->anti_xss($alamatemail)."',";
				$data[] = "'".$this->anti_xss($kelamin)."',";
				$data[] = "'".$this->anti_xss($username)."',";
				$data[] = "'".$this->anti_xss($passwordku)."',";
				
				$data[] = "0,";
				$data[] = "0,";
				$data[] = "'".date("Y-m-d H:i:s")."'";
				
				$cekdata="member_username LIKE '".$username."'";
				$cek_member = $this->mm->check_duplicate("tbl_member", "member" ,$cekdata);
				
				if($cek_member->jml === "0")
				{
					$insert_data = $this->mm->insert_data("tbl_member", $field, $data);
					if($insert_data === 1)
					{
						$rand  = rand();
						$msg   = base64_encode($rand."-Berhasil Daftar");
						$alert = base64_encode($rand."-success");
						redirect(site_url("auth/index?m=".$msg."&a=".$alert));
					}
					else
					{
						$rand  = rand();
						$msg   = base64_encode($rand."-Tidak Berhasil Daftar");
						$alert = base64_encode($rand."-danger");
						redirect(site_url("auth/index?m=".$msg."&a=".$alert));
					}
				}
				else
				{
					?>
					<script type="text/javascript">
					alert("Username sudah ada");
					window.history.back();
					</script>
					<?php
				}
			}
		}
		else
		{
			redirect(site_url("auth"));
		}
	}
	
	function cek_kata($teks) 
	{
		$kata = array("tes","test");	 
		$hasil = 0;
		$jml_kata = count($kata);
	
		for ($i=0;$i<$jml_kata;$i++)
		{		 
			if (stristr($teks,$kata[$i]))
			{ $hasil=1; } 
		}

		return $hasil; 
	}
	
	function disabled(){
		$this->session->sess_destroy();
		$this->load->view('template/info_javascript');
	}
	
	function warning()
	{
		$data["page"]  = "template/info_access";
        $data["title"] = "Input is Denied";
		$this->load->view('template/template', $data);
	}
	
	function forbidden()
	{
		$data["page"]  = "template/info_access";
        $data["title"] = "Access is Denied";
		$this->load->view('template/template', $data);
	}
	
	function anti_xss($string)
	{
		$filter=stripslashes(strip_tags(htmlspecialchars(trim($string),ENT_QUOTES)));
		return $filter;
	}
}