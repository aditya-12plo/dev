<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen extends Controller {
	public $content;
	
	public function __construct() {
        parent::__construct();
		$this->load->library('newtable_edit');
    }
	
	function index(){
		$add_header  = '<link rel="stylesheet" href="'.base_url().'assets/vendor/sweetalert/dist/sweetalert.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/css/app.min.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/css/bootstrap-extend.min.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/css/newtable.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/vendor/themes/twitter/twitter.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/css/jquery-ui.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/css/alerts.css">';
		$add_header .= '<link rel="stylesheet" href="'.base_url().'assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">';
		$add_header .= '<script src="'.base_url().'assets/js/jquery.min.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/js/jquery-ui.min.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/js/newtable.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/js/main.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/vendor/sweetalert/dist/sweetalert.min.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/vendor/noty/js/noty/packaged/jquery.noty.packaged.min.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/js/helpers/noty-defaults.js"></script>';
		$add_header .= '<script src="'.base_url().'assets/js/alerts.js"></script>';
		$add_script  = '<script src="'.base_url().'assets/js/app.min.js"></script>';
		$add_script .= '<script src="'.base_url().'assets/js/jquery-ui.js"></script>';
		$add_script .= '<script src="'.base_url().'assets/js/ui/notifications.js"></script>';
		$add_script .= '<script src="'.base_url().'assets/vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>';
		if($this->newsession->userdata('LOGGED')){
			if($this->content==""){
				$this->content = $this->load->view('content/dashboard/index','',true);
			}
			$data = array('_add_header_'   => $add_header,
						  '_add_script_'   => $add_script,
						  '_tittle_'  	   => 'TPS ONLINE',
						  '_header_'  	   => $this->load->view('content/header','',true),
						  '_breadcrumbs_'  => $this->load->view('content/breadcrumbs','',true),
						  '_menus_'   	   => $this->load->view('content/menus','',true),
						  '_content_' 	   => (grant()=="")?$this->load->view('content/error','',true):$this->content,
						  '_footer_'  	   => $this->load->view('content/footer','',true),
						  '_features_'     => $this->load->view('content/features','',true));
			$this->parser->parse('index', $data);
		}else{
			redirect(base_url('index.php'),'refresh');
		}
	}
	
	public function impor($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="detail"){
			$arrid = explode('~',$id); 
			$this->load->model('m_execute');
			$data['title'] = 'DETAIL RESPON BEA CUKAI - EKSPOR KONTAINER';
			$data['arrdata'] = $this->m_execute->get_data('permit_cont', $id);
			echo $this->load->view('content/dokumen/kontainer-detail',$data,true);
		}else{
			$this->newtable->breadcrumb('Home', site_url());
			$this->newtable->breadcrumb('Respon Bea Cukai', 'javascript:void(0)');
			$this->newtable->breadcrumb('Dokumen Impor', 'javascript:void(0)');
			$data['page_title'] = 'DOKUMEN IMPOR';
			#$data['table_request'] = $this->impor_request_kontainer($act,$id);
			$data['table_kontainer'] = $this->impor_kontainer($act,$id);
			$this->content = $this->load->view('content/dokumen/index',$data,true);
			$this->index();
		}
	}
	
	public function impor_request_kontainer($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="add"){
			$this->load->model('m_popup');
			$data['title'] = 'REQUEST DOKUMEN IMPOR - KONTAINER';
			$data['action'] = 'save';
			$data['url'] = 'impor_request_kontainer';
			$data['arr_dokumen'] = $this->m_popup->get_combobox('dok_bc','IMP');
			echo $this->load->view('content/dokumen/request',$data,true);
		}if($act=="update"){
			$this->load->model('m_execute');
			$this->load->model('m_popup');
			$data['title'] = 'UPDATE DOKUMEN IMPOR - KONTAINER';
			$data['id'] = $id;
			$data['action'] = 'update';
			$data['url'] = 'impor_request_kontainer';
			$data['arrdata'] = $this->m_execute->get_data('custimp_hdr', $id);
			$data['arr_dokumen'] = $this->m_popup->get_combobox('dok_bc','IMP');
			echo $this->load->view('content/dokumen/request',$data,true);
		}if($act=="detail"){
			$this->load->model('m_execute');
			$this->load->model('m_popup');
			$data['title'] = 'UPDATE DOKUMEN IMPOR - KONTAINER';
			$data['id'] = $id;
			$data['action'] = 'detail';
			$data['url'] = 'impor_request_kontainer';
			$data['arrdata'] = $this->m_execute->get_data('custimp_hdr', $id);
			$data['arr_dokumen'] = $this->m_popup->get_combobox('dok_bc','IMP');
			echo $this->load->view('content/dokumen/request',$data,true);
		}else{
			$this->load->model("m_dokumen");
			$arrdata = $this->m_dokumen->impor_request_kontainer($act, $id);
			$data = $this->load->view('content/newtable', $arrdata, true);
			if($this->input->post("ajax")||$act=="post"){
				echo $arrdata;
			}else{
				return $data;
			}	
		}
	}
	
	public function impor_kontainer($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="detail"){	
			$arrid = explode('~',$id); 
			$this->load->model('m_execute');
			$data['title'] = 'DETAIL RESPON DOKUMEN IMPOR - KONTAINER';
			$data['arrdata'] = $this->m_execute->get_data('permit_hdr', $arrid[0]);
			$data['table_detail'] = $this->kontainer_detail($act,$arrid[0]);
			echo $this->load->view('content/dokumen/kontainer-detail',$data,true);
			//print_r($data['table_detail']);
		}else{
			$this->load->model("m_dokumen");			
			$arrdata = $this->m_dokumen->impor_kontainer($act, $id);			
			$data = $this->load->view('content/newtable', $arrdata, true);
			if($this->input->post("ajax")||$act=="post"){
				echo $arrdata;
			}else{
				return $data;
			}	
		}
	}
	
	public function ekspor($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="detail"){
			$arrid = explode('~',$id); 
			$this->load->model('m_execute');
			$data['title'] = 'DETAIL RESPON BEA CUKAI - EKSPOR KONTAINER';
			$data['arrdata'] = $this->m_execute->get_data('permit_cont', $id);
			echo $this->load->view('content/dokumen/kontainer-detail',$data,true);
		}else{
			$this->newtable->breadcrumb('Home', site_url());
			$this->newtable->breadcrumb('Respon Bea Cukai', 'javascript:void(0)');
			$this->newtable->breadcrumb('Dokumen Ekspor', 'javascript:void(0)');
			$data['page_title'] = 'DOKUMEN EKSPOR';
			$data['table_request'] = $this->ekspor_request_kontainer($act,$id);
			$data['table_kontainer'] = $this->ekspor_kontainer($act,$id);
			$this->content = $this->load->view('content/dokumen/index',$data,true);
			$this->index();
		}
	}
	
	public function ekspor_request_kontainer($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="add"){
			$this->load->model('m_popup');
			$data['title'] = 'REQUEST DOKUMEN EKSPOR - KONTAINER';
			$data['action'] = 'save';
			$data['url'] = 'ekspor';
			$data['arr_dokumen'] = $this->m_popup->get_combobox('dok_bc','EXP');
			echo $this->load->view('content/dokumen/request',$data,true);
		}if($act=="update"){
			$this->load->model('m_execute');
			$this->load->model('m_popup');
			$data['title'] = 'UPDATE DOKUMEN EKSPOR - KONTAINER';
			$data['id'] = $id;
			$data['action'] = 'update';
			$data['url'] = 'ekspor_request_kontainer';
			$data['arrdata'] = $this->m_execute->get_data('custimp_hdr', $id);
			$data['arr_dokumen'] = $this->m_popup->get_combobox('dok_bc','EXP');
			echo $this->load->view('content/dokumen/request',$data,true);
		}else{
			$this->load->model("m_dokumen");
			$arrdata = $this->m_dokumen->ekspor_request_kontainer($act, $id);
			$data = $this->load->view('content/newtable', $arrdata, true);
			if($this->input->post("ajax")||$act=="post"){
				echo $arrdata;
			}else{
				return $data;
			}	
		}
	}
	
	public function ekspor_kontainer($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		if($act=="detail"){
			$arrid = explode('~',$id); 
			$this->load->model('m_execute');
			$data['title'] = 'DETAIL RESPON DOKUMEN EKSPOR - KONTAINER';
			$data['arrdata'] = $this->m_execute->get_data('permit_hdr', $id);
			$data['table_detail'] = $this->kontainer_detail($act,$id);
			echo $this->load->view('content/dokumen/kontainer-detail',$data,true);
		}else{
			$this->load->model("m_dokumen");
			$arrdata = $this->m_dokumen->ekspor_kontainer($act, $id);
			$data = $this->load->view('content/newtable', $arrdata, true);
			if($this->input->post("ajax")||$act=="post"){
				echo $arrdata;
			}else{
				return $data;
			}	
		}
	}
	
	function kontainer_detail($act,$id){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$this->load->model("m_dokumen");
		$arrdata = $this->m_dokumen->kontainer_detail($act, $id);
		$data = $this->load->view('content/newtable', $arrdata, true);
		if($this->input->post("ajax")||$act=="post"){
			echo $arrdata;
		}else{
			return $data;
		}	
	}


		function process($type="",$act="", $id=""){
		if (!$this->newsession->userdata('LOGGED')) {
			$this->index();
			return;
		}else{
			if (strtolower($_SERVER['REQUEST_METHOD']) != "post") {
				echo 'access is forbidden'; exit();
			}else{
				$this->load->model("m_execute");
				$this->m_execute->process($type,$act,$id);
			}
		}
	}
}
