<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_gate extends Model{

	/*
	public function gateout($act, $id){
		$page_title = "GATE OUT";
		$title = "GATE OUT";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_GROUP = $this->newsession->userdata('KD_GROUP');
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Container', site_url('gate/out_container'),'');
		$this->newtable->breadcrumb('Gate Out', 'javascript:void(0)','');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND A.KD_TPS = ".$this->db->escape($KD_TPS)." AND A.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post()){
			$addsql .= " AND A.TGL_TIBA >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT CONCAT(C.NAMA,'<BR>[',A.NM_ANGKUT,']') AS 'NAMA ANGKUT', 
				A.NO_VOY_FLIGHT AS 'NO. VOYAGE/FLIGHT', 
				DATE_FORMAT(A.TGL_TIBA,'%d-%m-%Y') AS 'TGL. TIBA', A.NO_BC11 AS 'NO. BC11',
				DATE_FORMAT(A.TGL_BC11,'%d-%m-%Y') AS 'TGL. BC11', A.WK_REKAM AS 'WAKTU REKAM',
				CONCAT('<span class=\"label label-danger\">JUMLAH : ',(
									SELECT COUNT(X.ID) FROM t_cocostscont X WHERE X.ID = A.ID),
				'</span><BR><span class=\"label label-info\">DISCHARGE : ',(
									SELECT COUNT(X.ID) FROM t_cocostscont X 
									WHERE X.WK_IN IS NOT NULL 
									AND X.WK_OUT IS NOT NULL 
									AND X.ID = A.ID),
				'</span><BR><span class=\"label label-success\">GATE OUT : ',(SELECT COUNT(X.ID) 
								 	FROM t_cocostscont X 
									WHERE X.WK_IN IS NOT NULL 
									AND X.WK_OUT IS NOT NULL 
									AND X.ID = A.ID),'<span>') AS 'KONTAINER', A.ID
				FROM t_cocostshdr A 
				LEFT JOIN reff_gudang B ON A.KD_TPS = B.KD_TPS AND A.KD_GUDANG = B.KD_GUDANG 
				LEFT JOIN reff_kapal C ON A.KD_KAPAL = C.ID";
				#WHERE 1=1 ".$addsql;
		$proses = array('DETAIL' => array('GET',site_url()."/gate/out_container/detail", '1','','glyphicon glyphicon-zoom-in'),
						'UPLOAD' => array('ADD',site_url()."/gate/out_container/upload", '','','md-attachment'));
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_BC11','NO. BC11'),array('C.NAMA','NAMA ANGKUT'),array('A.TGL_TIBA','TANGGAL TIBA','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/out_container");
		if($check) $this->newtable->detail(array('GET',site_url()."/gate/out_container/detail"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID"));
		$this->newtable->keys(array("ID"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkapal");
		$this->newtable->set_divid("divtblkapal");
		$this->newtable->rowcount(10);
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("page_title" => $page_title, "title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act == "post")
			return $tabel;
		else
			return $arrdata;
	}
	*/
	public function gateout($act, $id){
		$page_title = "GATE OUT";
		$title = "GATE OUT";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_GROUP = $this->newsession->userdata('KD_GROUP');
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Container', site_url('gate/out_container'),'');
		$this->newtable->breadcrumb('Gate Out', 'javascript:void(0)','');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND A.KD_TPS = ".$this->db->escape($KD_TPS)." AND A.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post()){
			$addsql .= " AND A.TGL_TIBA >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_CONT AS 'NO KONTAINER', DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE OUT',B.NO_BC11 AS 'NO BC11',B.TGL_BC11 AS 'TGL BC 11',B.NM_ANGKUT AS 'NAMA KAPAL',
				B.NO_VOY_FLIGHT AS 'NO VOYAGE', DATE_FORMAT(B.TGL_TIBA,'%d-%m-%Y') AS 'TGL TIBA',
				A.NO_CONT
				FROM t_cocostscont A LEFT JOIN t_cocostshdr B ON A.ID=B.ID 
				WHERE A.WK_IN IS NOT NULL AND A.WK_OUT IS NOT NULL";
				#WHERE 1=1 ".$addsql;
		/*
		$proses = array('DETAIL' => array('GET',site_url()."/gate/out_container/detail", '1','','glyphicon glyphicon-zoom-in'),
						'UPLOAD' => array('ADD',site_url()."/gate/out_container/upload", '','','md-attachment'));
		*/
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk(false);
		#$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT','NO. KONTAINER'),array('B.NO_BC11','NO. BC11'),array('B.NM_ANGKUT','NAMA ANGKUT'),array('B.TGL_TIBA','TANGGAL TIBA','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/out_container");
		#if($check) $this->newtable->detail(array('GET',site_url()."/gate/out_container/detail"));
		#$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("NO_CONT"));
		$this->newtable->keys(array("NO_CONT"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount(10);
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("page_title" => $page_title, "title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act == "post")
			return $tabel;
		else
			return $arrdata;
	}
	
	public function kontainer_detail($act,$id){
		$page_title = "GATE OUT - KONTAINER";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_CONT AS 'NO. KONTAINER', func_name(IFNULL(A.KD_CONT_UKURAN,'-'),'CONT_UKURAN') AS 'UKURAN KONTAINER',
				func_name(IFNULL(A.KD_CONT_JENIS,'-'),'CONT_JENIS') AS 'JENIS KONTAINER',
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB', A.BRUTO,
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'DISCHARGE',
				DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE OUT', 
				DATE_FORMAT(IFNULL(A.WK_REKAM,'-'),'%d-%m-%Y %H:%i:%s') AS 'WAKTU REKAM',
				'gate/GATEOUT_KONTAINER' AS POST, A.ID
				FROM t_cocostscont A
				WHERE A.WK_OUT IS NOT NULL AND A.ID = ".$this->db->escape($id);
		#$proses = array('GATE OUT' => array('MODAL',"gate/gateout_kontainer/update", '1','NOT-NULL','md-redo','','1'));
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT', 'NOMOR KONTAINER'),array('A.WK_OUT','GATE OUT','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/kontainer_detail/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/kontainer_detail/detail-kontainer"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID",'POST'));
		$this->newtable->keys(array("ID","NO. KONTAINER",'POST'));
		$this->newtable->validasi(array("GATE OUT"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(9);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	
	public function gateout_kontainer($act,$id){
		$page_title = "GATE OUT - KONTAINER";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_CONT AS 'NO. KONTAINER', func_name(IFNULL(A.KD_CONT_UKURAN,'-'),'CONT_UKURAN') AS 'UKURAN KONTAINER',
				func_name(IFNULL(A.KD_CONT_JENIS,'-'),'CONT_JENIS') AS 'JENIS KONTAINER',
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB', A.BRUTO,
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'DISCHARGE',
				DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE OUT', 
				DATE_FORMAT(IFNULL(A.WK_REKAM,'-'),'%d-%m-%Y %H:%i:%s') AS 'WAKTU REKAM',
				'gate/GATEOUT_KONTAINER' AS POST, A.ID
				FROM t_cocostscont A
				WHERE A.WK_IN IS NOT NULL AND A.ID = ".$this->db->escape($id).$addsql;
		$proses = array('GATE OUT' => array('MODAL',"gate/gateout_kontainer/update", '1','NOT-NULL','md-redo','','1'));
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT', 'NOMOR KONTAINER'),array('A.WK_OUT','GATE OUT','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/gateout_kontainer/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/gateout_kontainer/detail-kontainer"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID",'POST'));
		$this->newtable->keys(array("ID","NO. KONTAINER",'POST'));
		$this->newtable->validasi(array("GATE OUT"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(9);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	/*
	public function gateout_kemasan($act,$id){
		$page_title = "GATE OUT - KEMASAN";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND B.KD_TPS = ".$this->db->escape($KD_TPS)." AND B.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_OUT >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT CONCAT('JUMLAH : ',A.JUMLAH,'<BR>BRUTO : ',A.BRUTO,'<BR>',
				func_name(A.KD_KEMASAN,'KEMASAN'),' [',A.KD_KEMASAN,']') AS KEMASAN,
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB',
				A.NO_POS_BC11 AS 'POS BC11', C.NAMA AS CONISGNEE,
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'DISCHARGE',
				DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE OUT',
				A.WK_REKAM AS 'WAKTU REKAM', A.ID, A.SERI, 'gate/GATEOUT_KEMASAN' AS POST
				FROM t_cocostskms A
				INNER JOIN t_cocostshdr B ON B.ID=A.ID
				LEFT JOIN t_organisasi C ON C.ID=A.KD_ORG_CONSIGNEE
				WHERE A.WK_IN IS NOT NULL AND A.ID = ".$this->db->escape($id).$addsql;
		$proses = array('UPDATE' => array('MODAL',"gate/gateout_kemasan/update", '1','','md-redo','','1'));
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_MASTER_BL_AWB', 'MASTER BL/AWB'),array('A.NO_BL_AWB', 'BL/AWB')));
		$this->newtable->action(site_url() . "/gate/gateout_kemasan/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/gateout_kemasan/detail-kemasan"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID","SERI","POST"));
		$this->newtable->keys(array("ID","SERI","POST"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(10);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkemasan");
		$this->newtable->set_divid("divtblkemasan");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	*/
	public function gateout_kemasan($act,$id){
		$page_title = "GATE OUT - KEMASAN";
		$title = "DELIVERY";
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Kemasan', site_url('gate/out_kemasan'),'');
		$this->newtable->breadcrumb('DELIVERY', 'javascript:void(0)','');
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND B.KD_TPS = ".$this->db->escape($KD_TPS)." AND B.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_OUT >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_BL_AWB AS 'NO BL',A.JUMLAH AS 'JUMLAH KEMASAN',B.NAMA AS 'JENIS KEMASAN',
				A.NO_CONT_ASAL AS 'KONTAINER ASAL',C.NM_ANGKUT AS 'NAMA KAPAL',C.NO_VOY_FLIGHT AS 'NO VOYAGE',
				CONCAT('DELIVERY : ',DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s')) AS 'DELIVERY',CONCAT('NAMA TPS : ',D.NAMA_TPS,'<BR>NAMA GUDANG : ',E.NAMA_GUDANG) AS 'LOKASI TIMBUN',A.NO_BL_AWB,A.NO_MASTER_BL_AWB
		FROM t_cocostskms A LEFT JOIN reff_kemasan B ON A.KD_KEMASAN=B.ID LEFT JOIN t_cocostshdr C ON A.ID=C.ID INNER JOIN reff_gudang E ON A.KD_GUDANG_TUJUAN=E.KD_GUDANG INNER JOIN reff_tps D ON E.KD_TPS=D.KD_TPS
		WHERE A.WK_IN IS NOT NULL AND A.WK_OUT IS NOT NULL";
		$this->newtable->show_chk(FALSE);
		#$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_MASTER_BL_AWB', 'MASTER BL/AWB'),array('A.NO_BL_AWB', 'BL/AWB')));
		$this->newtable->action(site_url() . "/gate/out_kemasan");
		#if($check) $this->newtable->detail(array('POPUP',"gate/gateout_kemasan/detail-kemasan"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("NO_BL_AWB","NO_MASTER_BL_AWB"));
		$this->newtable->keys(array("NO_BL_AWB"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkemasan");
		$this->newtable->set_divid("divtblkemasan");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	
	/*
	public function in_container($act, $id){
		$page_title = "GATE IN";
		$title = "GATE IN";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_GROUP = $this->newsession->userdata('KD_GROUP');
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Kontainer', site_url('/gate/in_container'),'');
		$this->newtable->breadcrumb('Gate In', 'javascript:void(0)','');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND A.KD_TPS = ".$this->db->escape($KD_TPS)." AND A.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.TGL_TIBA >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}

		$SQL = "SELECT CONCAT(C.NAMA,'<BR>[',A.NM_ANGKUT,']') AS 'NAMA ANGKUT', 
				A.NO_VOY_FLIGHT AS 'NO. VOYAGE/FLIGHT', 
				DATE_FORMAT(A.TGL_TIBA,'%d-%m-%Y') AS 'TGL. TIBA', A.NO_BC11 AS 'NO. BC11',
				DATE_FORMAT(A.TGL_BC11,'%d-%m-%Y') AS 'TGL. BC11', A.WK_REKAM AS 'WAKTU REKAM',
				CONCAT('<span class=\"label label-danger\">JUMLAH : ',(
									SELECT COUNT(X.ID) FROM t_cocostscont X WHERE X.ID = A.ID),
				'</span><BR><span class=\"label label-info\">GATE IN : ',(
									SELECT COUNT(X.ID) FROM t_cocostscont X 
									WHERE X.WK_IN IS NOT NULL 
									AND X.WK_OUT IS NULL 
									AND X.ID = A.ID),
				'</span><BR><span class=\"label label-success\">LOADING : ',(SELECT COUNT(X.ID) 
								 	FROM t_cocostscont X 
									WHERE X.WK_IN IS NOT NULL 
									AND X.WK_OUT IS NOT NULL 
									AND X.ID = A.ID),'<span>') AS 'KONTAINER',
				A.ID
				FROM t_cocostshdr A 
				LEFT JOIN reff_gudang B ON A.KD_TPS = B.KD_TPS AND A.KD_GUDANG = B.KD_GUDANG 
				LEFT JOIN reff_kapal C ON A.KD_KAPAL = C.ID";
				//WHERE A.KD_ASAL_BRG = '3'".$addsql;
				
		
		$proses = array('ENTRY'  => array('ADD_MODAL',"gate/in_container/add", '0','',''),
						'UPDATE' => array('GET',site_url()."/gate/in_container/update", '1','',''),
						'DETAIL' => array('GET',site_url()."/gate/in_container/detail", '1','',''),
						'UPLOAD' => array('ADD',site_url()."/gate/in_container/upload", '','',''));
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_BC11','NO. BC11'),array('C.NAMA','NAMA ANGKUT'),array('A.TGL_TIBA','TANGGAL TIBA','DATERANGE')));
		$this->newtable->action(site_url()."/gate/in_container");
		if($check) $this->newtable->detail(array('GET',site_url()."/gate/in_container/detail"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID"));
		$this->newtable->keys(array("ID"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkapal");
		$this->newtable->set_divid("divtblkapal");
		$this->newtable->rowcount(10);
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("page_title" => $page_title, "title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act == "post")
			return $tabel;
		else
			return $arrdata;
	}
	*/
	public function in_container($act, $id){
		$page_title = "GATE IN";
		$title = "GATE IN";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_GROUP = $this->newsession->userdata('KD_GROUP');
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Kontainer', site_url('/gate/in_container'),'');
		$this->newtable->breadcrumb('Gate In', 'javascript:void(0)','');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND B.KD_TPS = ".$this->db->escape($KD_TPS)." AND B.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.TGL_TIBA >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}

		$SQL = "SELECT A.NO_CONT AS 'NO KONTAINER', DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE IN',
				B.NO_BC11 AS 'NO BC11',B.TGL_BC11 AS 'TGL BC 11',B.NM_ANGKUT AS 'NAMA KAPAL',
				B.NO_VOY_FLIGHT AS 'NO VOYAGE', DATE_FORMAT(B.TGL_TIBA,'%d-%m-%Y') AS 'TGL TIBA',
				A.NO_CONT
				FROM t_cocostscont A LEFT JOIN t_cocostshdr B ON A.ID=B.ID WHERE A.WK_IN IS NOT NULL AND A.WK_OUT IS NULL";
				//WHERE A.KD_ASAL_BRG = '3'".$addsql;
				
		/*
		$proses = array('ENTRY'  => array('ADD_MODAL',"gate/in_container/add", '0','',''),
						'UPDATE' => array('GET',site_url()."/gate/in_container/update", '1','',''),
						'DETAIL' => array('GET',site_url()."/gate/in_container/detail", '1','',''),
						'UPLOAD' => array('ADD',site_url()."/gate/in_container/upload", '','',''));
		*/
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk(false);
		#$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT','NO. KONTAINER'),array('B.NO_BC11','NO. BC11'),array('B.NM_ANGKUT','NAMA ANGKUT'),array('B.TGL_TIBA','TANGGAL TIBA','DATERANGE')));
		$this->newtable->action(site_url()."/gate/in_container");
		#if($check) $this->newtable->detail(array('GET',site_url()."/gate/in_container/detail"));
		#$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("NO_CONT"));
		$this->newtable->keys(array("NO_CONT"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount(10);
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("page_title" => $page_title, "title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act == "post")
			return $tabel;
		else
			return $arrdata;
	}
	
	public function gatein_kontainer($act,$id){
		$page_title = "GATE IN - KONTAINER";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_CONT AS 'NO. KONTAINER', func_name(IFNULL(A.KD_CONT_UKURAN,'-'),'CONT_UKURAN') AS 'UKURAN KONTAINER',
				func_name(IFNULL(A.KD_CONT_JENIS,'-'),'CONT_JENIS') AS 'JENIS KONTAINER',
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB', A.BRUTO,
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE IN', 
				DATE_FORMAT(IFNULL(A.WK_REKAM,'-'),'%d-%m-%Y %H:%i:%s') AS 'WAKTU REKAM',
				'gate/GATEIN_KONTAINER' AS POST, A.ID
				FROM t_cocostscont A
				WHERE A.ID = ".$this->db->escape($id);
		
		$proses = array('ENTRY' => array('MODAL',"gate/gatein_kontainer/add/".$id, '0','','glyphicon glyphicon-plus-sign','','1'),
						'UPDATE' => array('MODAL',"gate/gatein_kontainer/update", '1','','glyphicon glyphicon-edit','','1'),
						'DELETE' => array('DELETE',"execute/process/delete/kontainer", 'ALL','','glyphicon glyphicon-remove-circle','','1'));
		
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT', 'NOMOR KONTAINER'),array('A.WK_IN', 'GATE IN','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/gatein_kontainer/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/gatein_kontainer/detail-kontainer"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID",'POST'));
		$this->newtable->keys(array("ID","NO. KONTAINER",'POST'));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(8);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	
	public function kontainer_masuk($act,$id){
		$page_title = "GATE IN - KONTAINER";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_CONT AS 'NO. KONTAINER', func_name(IFNULL(A.KD_CONT_UKURAN,'-'),'CONT_UKURAN') AS 'UKURAN KONTAINER',
				func_name(IFNULL(A.KD_CONT_JENIS,'-'),'CONT_JENIS') AS 'JENIS KONTAINER',
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB', A.BRUTO,
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE IN', 
				DATE_FORMAT(IFNULL(A.WK_REKAM,'-'),'%d-%m-%Y %H:%i:%s') AS 'WAKTU REKAM',
				'gate/GATEIN_KONTAINER' AS POST, A.ID
				FROM t_cocostscont A
				WHERE A.ID = ".$this->db->escape($id);
		/*
		$proses = array('ENTRY' => array('MODAL',"gate/gatein_kontainer/add/".$id, '0','','glyphicon glyphicon-plus-sign','','1'),
						'UPDATE' => array('MODAL',"gate/gatein_kontainer/update", '1','','glyphicon glyphicon-edit','','1'),
						'DELETE' => array('DELETE',"execute/process/delete/kontainer", 'ALL','','glyphicon glyphicon-remove-circle','','1'));
		*/
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_CONT', 'NOMOR KONTAINER'),array('A.WK_IN', 'GATE IN','DATERANGE')));
		$this->newtable->action(site_url() . "/gate/kontainer_masuk/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/kontainer_masuk/detail-kontainer"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID",'POST'));
		$this->newtable->keys(array("ID","NO. KONTAINER",'POST'));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(8);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkontainer");
		$this->newtable->set_divid("divtblkontainer");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
	
	/*
	public function gatein_kemasan($act,$id){
		$page_title = "GATE IN - KEMASAN";
		$title = "";
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND B.KD_TPS = ".$this->db->escape($KD_TPS)." AND B.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT CONCAT('JUMLAH : ',A.JUMLAH,'<BR>BRUTO : ',A.BRUTO,'<BR>',
				func_name(A.KD_KEMASAN,'KEMASAN'),' [',A.KD_KEMASAN,']') AS KEMASAN,
				CONCAT('NO. ',IFNULL(NO_MASTER_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'-')) AS 'MASTER BL/AWB',
				CONCAT('NO. ',IFNULL(NO_BL_AWB,'-'),'<BR>TGL. ',IFNULL(DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'-')) AS 'BL/AWB',
				A.NO_POS_BC11 AS 'NO. POS BC11', C.NAMA AS CONISGNEE, 
				DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE IN', A.WK_REKAM AS 'TANGGAL REKAM', A.ID, A.SERI,
				'gate/GATEIN_KEMASAN' AS POST
				FROM t_cocostskms A
				INNER JOIN t_cocostshdr B ON B.ID=A.ID
				LEFT JOIN t_organisasi C ON C.ID=A.KD_ORG_CONSIGNEE";
				#WHERE A.ID = ".$this->db->escape($id).$addsql;
		$proses = array('ENTRY' => array('MODAL',"gate/gatein_kemasan/add/".$id, '0','','glyphicon glyphicon-plus-sign','','1'),
						'UPDATE' => array('MODAL',"gate/gatein_kemasan/update", '1','','glyphicon glyphicon-edit','','1'),
						'DELETE' => array('DELETE',"execute/process/delete/kemasan/".$id, 'ALL','','glyphicon glyphicon-remove-circle','','1'));
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_MASTER_BL_AWB', 'MASTER BL/AWB'),array('A.NO_BL_AWB', 'BL/AWB')));
		$this->newtable->action(site_url() . "/gate/gatein_kemasan/".$act."/".$id);
		if($check) $this->newtable->detail(array('POPUP',"gate/gatein_kemasan/detail-kemasan"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("ID","SERI","POST"));
		$this->newtable->keys(array("ID","SERI","POST"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(9);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkemasan");
		$this->newtable->set_divid("divtblkemasan");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $judul, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}*/
	public function gatein_kemasan($act,$id){
		$page_title = "GATE IN - KEMASAN";
		$title = "RECEIVING";
		$this->newtable->breadcrumb('Home', site_url(),'');
		$this->newtable->breadcrumb('Kemasan', site_url('gate/in_kemasan'),'');
		$this->newtable->breadcrumb('RECEIVING', 'javascript:void(0)','');
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$check = (grant()=="W")?true:false;
		if($KD_GROUP!="SPA"){
			$addsql .= " AND B.KD_TPS = ".$this->db->escape($KD_TPS)." AND B.KD_GUDANG = ".$this->db->escape($KD_GUDANG);
		}
		if(!$this->input->post('ajax')){
			$addsql .= " AND A.WK_IN >= DATE_ADD(CURDATE(), INTERVAL -7 DAY)";
		}
		$SQL = "SELECT A.NO_BL_AWB AS 'NO BL',A.JUMLAH AS 'JUMLAH KEMASAN',B.NAMA AS 'JENIS KEMASAN',
				A.NO_CONT_ASAL AS 'KONTAINER ASAL',C.NM_ANGKUT AS 'NAMA KAPAL',C.NO_VOY_FLIGHT AS 'NO VOYAGE',
				CONCAT('RECEIVING : ',DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s'),'<BR>TGL TIBA : ',DATE_FORMAT(IFNULL(C.TGL_TIBA,'-'),'%d-%m-%Y')) AS 'RECEIVING',CONCAT('NAMA TPS : ',D.NAMA_TPS,'<BR>NAMA GUDANG : ',E.NAMA_GUDANG) AS 'LOKASI TIMBUN',A.NO_BL_AWB,A.NO_MASTER_BL_AWB
		FROM t_cocostskms A LEFT JOIN reff_kemasan B ON A.KD_KEMASAN=B.ID LEFT JOIN t_cocostshdr C ON A.ID=C.ID INNER JOIN reff_gudang E ON A.KD_GUDANG_TUJUAN=E.KD_GUDANG INNER JOIN reff_tps D ON E.KD_TPS=D.KD_TPS
		WHERE A.WK_IN IS NOT NULL AND A.WK_OUT IS NULL";
				#WHERE A.ID = ".$this->db->escape($id).$addsql;

		$this->newtable->show_chk(false);
		#$this->newtable->show_menu($check);
		$this->newtable->multiple_search(true);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_MASTER_BL_AWB', 'MASTER BL/AWB'),array('A.NO_BL_AWB', 'BL/AWB')));
		$this->newtable->action(site_url() . "/gate/in_kemasan/");
		#if($check) $this->newtable->detail(array('POPUP',"gate/gatein_kemasan/detail-kemasan"));
		#$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("NO_BL_AWB","NO_MASTER_BL_AWB"));
		$this->newtable->keys(array("NO_BL_AWB"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(6);
		$this->newtable->sortby("DESC");
		$this->newtable->set_formid("tblkemasan");
		$this->newtable->set_divid("divtblkemasan");
		$this->newtable->rowcount('10');
		$this->newtable->clear();
		$this->newtable->menu($proses);
		$tabel .= $this->newtable->generate($SQL);
		$arrdata = array("title" => $title, "content" => $tabel);
		if($this->input->post("ajax")||$act=="post")
			echo $tabel;
		else
			return $arrdata;
	}
}