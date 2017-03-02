<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_ppbarang extends Model{

  function M_ppbarang() {
     parent::Model();
  }

	function listdata($act, $id){
		$this->newtable->breadcrumb('Home', site_url());
		$this->newtable->breadcrumb('Permohonan Pengeluaran Barang', 'javascript:void(0)');
		$data['title'] = 'PERMOHONAN PENGELUARAN BARANG';
		$judul = "PERMOHONAN PENGELUARAN BARANG";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_GROUP = $this->newsession->userdata('KD_GROUP');
		$check = (grant()=="W")?true:false;
		/*if($KD_GROUP!="SPA"){
			$addsql .= " AND A.KD_TPS_ASAL = ".$this->db->escape($KD_TPS)." AND A.KD_GUDANG_ASAL = ".$this->db->escape($KD_GUDANG);
		}
		$SQL = "SELECT IFNULL(A.REF_NUMBER,'-') AS 'NO. PIB',
				CONCAT('NO. : ',IFNULL(A.NO_SURAT,'-'),'<BR>TGL. : ',IFNULL(DATE_FORMAT(A.TGL_SURAT,'%d-%m-%Y'),'-')) AS 'NO. SPPB',
				A.NM_ANGKUT AS 'NAMA KAPAL', A.NO_VOY_FLIGHT AS 'NO VOYAGE', DATE_FORMAT(A.TGL_TIBA,'%d-%m-%Y') AS 'TGL. TIBA',
				CONCAT('NO. : ',A.NO_BC11,'<BR>TGL. : ',DATE_FORMAT(A.TGL_BC11,'%d-%m-%Y')) AS BC11,
				CONCAT('YOR ASAL : ',IFNULL(A.YOR_ASAL,'-'),'<BR> YOR TUJUAN : ',IFNULL(A.YOR_TUJUAN,'-')) AS 'YOR',
				CONCAT('TPS : ',A.KD_TPS_TUJUAN,'<BR>GUDANG : ',A.KD_GUDANG_TUJUAN) AS 'GUDANG TUJUAN',
				CONCAT(C.NAMA,'<BR>',DATE_FORMAT(A.TGL_STATUS,'%d-%m-%Y %H:%i:%s')) AS STATUS, A.TGL_STATUS,
				A.ID, A.KD_COCOSTSHDR, A.KD_STATUS
				FROM t_request_plp_hdr A
				LEFT JOIN reff_kapal B ON B.ID=A.KD_KAPAL
				LEFT JOIN reff_status C ON C.ID=A.KD_STATUS AND C.KD_TIPE_STATUS='PLPAJU'
				WHERE 1=1".$addsql;*/
		$SQL = "SELECT CONCAT('NO. : ',IFNULL(A.NO_PIB,'-'),'<BR>TGL. : ',IFNULL(DATE_FORMAT(A.TGL_PIB,'%d-%m-%Y'),'-')) AS 'NO. PIB',
				CONCAT('NO. : ',IFNULL(A.NO_SPPB,'-'),'<BR>TGL. : ',IFNULL(DATE_FORMAT(A.TGL_SPPB,'%d-%m-%Y'),'-')) AS 'NO. SPPB',
				A.KD_KPBC AS 'KODE KPBC',CONCAT('NAMA : ',A.NAMA_IMP,'<BR>NPWP. : ',A.NPWP_IMP) AS IMPORTIR,
				A.NO_BL AS 'NO BL', A.NAMA_KAPAL AS 'NAMA KAPAL', A.GUDANG AS 'GUDANG', A.ID_SPPB, A.CAR FROM t_sppb A	WHERE 1=1".$addsql;
		/*
		$proses = array('ENTRY'	  => array('MODAL',"plp/pengajuan_discharge", '','','md-plus-circle'),
						'UPDATE'  => array('GET',site_url()."/plp/pengajuan_plp/update", '1','100:500','md-edit'),
						'DELETE'  => array('DELETE',"execute/process/delete/pengajuan_plp", '1','100','md-close-circle'),
						'PROCESS' => array('POST',"execute/process/update/send_pengajuan_plp", '1','100','md-mail-send'),
						'DETAIL'  => array('MODAL',"plp/pengajuan_plp/detail", '1','','md-zoom-in'));
		*/
		$proses = array('ENTRY'	  => array('ADD_MODAL',"ppbarang/listdata/add", '0','','icon-plus', '', '1'));
		if(!$check) $proses = '';
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_SPPB','NO. SPPB'),array('A.NO_PIB','NO. PIB'),array('A.NAMA_KAPAL','NAMA KAPAL')));
		$this->newtable->action(site_url() . "/ppbarang/listdata");
		#if($check) $this->newtable->detail(array('POPUP',"ppbarang/listdata/detail"));
		$this->newtable->detail(array('POPUP',"ppbarang/listdata/detail"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("CAR","ID_SPPB"));
		$this->newtable->keys(array("CAR","ID_SPPB"));
		//$this->newtable->validasi(array("ID_SPPB"));
		$this->newtable->cidb($this->db);
		//$this->newtable->orderby(10);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblppbarang");
		$this->newtable->set_divid("divtblppbarang");
		$this->newtable->rowcount(10);
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("page_title" => $page_title, "title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act == "post")
			echo $tabel;
		else
			return $arrdata;
	}

	function execute($type, $act, $id) {
		$func = get_instance();
		$func->load->model("m_main", "main", true);
		$success = 0;
		$error = 0;
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_KPBC = $this->newsession->userdata('KD_KPBC');
		// for detail
		if ($type == "detail") {
			if($act == 't_request_plp_hdr'){
				$SQL = "SELECT * FROM t_request_plp_hdr A LEFT JOIN reff_kapal B ON B.ID=A.KD_KAPAL
						LEFT JOIN reff_status C ON C.ID=A.KD_STATUS WHERE A.ID = " . $this->db->escape($id);
				// print_r($SQL);die();
				$result = $func->main->get_result($SQL);
				if ($result) {
				  foreach ($SQL->result_array() as $row => $value) {
						$arrdata = $value;
				  }
				  return $arrdata;
				}else {
				  redirect(site_url(), 'refresh');
				}
			}
		}elseif ($type == "save") {
			if($act == 'sppb'){
				$DATA= array(
          'CAR'				=> $this->input->post('CAR'),
          'NO_SPPB'		=> $this->input->post('NO_SPPB'),
          'TGL_SPPB'	=> validate(date_input($this->input->post('TGL_SPPB'))),
          'NO_PIB'		=> $this->input->post('NO_PIB'),
          'TGL_PIB'		=> validate(date_input($this->input->post('TGL_PIB'))),
          'KD_KPBC'		=> $this->input->post('KD_KPBC'),
          'NPWP_IMP'	=> $this->input->post('NPWP_IMP'),
          'NAMA_IMP'	=> trim(validate($this->input->post('NAMA_IMP'))),
					'NO_BL'			=> $this->input->post('NO_BL'),
          'NAMA_KAPAL'=> trim(validate($this->input->post('NAMA_KAPAL'))),
          'GUDANG'		=> trim(validate($this->input->post('GUDANG')))
        );
				$result = $this->db->insert('t_sppb', $DATA);
				if ($result) {
						$func->main->get_log("add", "t_sppb");
						echo "MSG#OK#Data berhasil diproses#" . site_url() . "/ppbarang/listdata/post";
				} else {
						echo "MSG#ERR#" . $message . "#";
				}
			}
		}
		// for detail
	}
}
