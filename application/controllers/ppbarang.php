<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ppbarang extends Controller {
	var $content = "";
	function Ppbarang(){
		parent::Controller();
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
					'_tittle_'  	   => 'CFS CENTER',
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

	public function listdata($act="",$id=""){
		if (!$this->newsession->userdata('LOGGED')){
			$this->index();
			return;
		}
		$id = ($id!="")?$id:$this->input->post('id');
		$this->load->model('m_ppbarang');
		if($act=="add"){
			$data['title'] = 'ENTRY DATA';
			$data['act'] = 'save';
			echo $this->load->view('content/ppbarang/add',$data,true);
		}else	if($act=="detail"){
			$arrid = explode('~',$id);
			$this->load->model('m_execute');
			$data['title'] = 'DETAIL PENGAJUAN PLP';
			$data['arrdata'] = $this->m_execute->get_data('request_plp',$arrid[1]);
			$data['table_kontainer'] = $this->m_ppbarang->execute('detail','t_request_plp_hdr',$arrid[1]);
			//print_r($data['table_kontainer']);
			echo $this->load->view('content/ppbarang/detail',$data,true);
		}else{
			$arrdata = $this->m_ppbarang->listdata($act, $id);
			$data = $this->load->view('content/newtable', $arrdata, true);
			if($this->input->post("ajax")||$act=="post"){
				echo $arrdata;
			}else{
				$this->content = $data;
				$this->index();
			}
		}
	}

	function execute($type="",$act="", $id=""){
		if (!$this->newsession->userdata('LOGGED')) {
			$this->index();
			return;
		}else{
			if (strtolower($_SERVER['REQUEST_METHOD']) != "post") {
				redirect(base_url());
				exit();
			}else{
				$this->load->model("m_ppbarang");
				$this->m_ppbarang->execute($type,$act,$id);
			}
		}
	}
}
