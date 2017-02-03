<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class M_status extends Model {
  function M_status() {
    parent::Model();
  } 

function listdata($act, $id) {
    $this->newtable->breadcrumb('Home', site_url());
    $this->newtable->breadcrumb('UBAH STATUS', 'javascript:void(0)');
    $data['title'] = 'DATA UBAH STATUS';
    $judul = "UBAH STATUS";
    //$KD_TPS = $this->newsession->userdata('KD_TPS');
    $KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
    $KD_GROUP = $this->newsession->userdata('KD_GROUP');
    $addsql = '';
    if ($KD_GROUP == "USER") {
      $addsql .= " AND   A.KD_GUDANG_ASAL = " . $this->db->escape($KD_GUDANG);
    }else if($KD_GROUP == "CONS"){
      $addsql .= " AND A.ID_USER = '".$this->newsession->userdata('ID')."' ";
    }
  $SQL = "SELECT A.ID,A.NO_UBAH_STATUS AS 'NO. UBAH STATUS' , 
          DATE_FORMAT(A.TGL_UBAH_STATUS,'%d-%m-%Y') AS 'TGL. UBAH STATUS',
          CONCAT('TPS ASAL : ',B.NAMA_TPS,'<BR>NAMA GUDANG : ',C.NAMA_GUDANG) AS 'GUDANG ASAL',
          CONCAT('TPS TUJUAN : ',D.NAMA_TPS,'<BR>NAMA GUDANG : ',E.NAMA_GUDANG) AS 'GUDANG TUJUAN',F.NM_LENGKAP AS 'NAMA PEMOHON',
          CONCAT('NAMA KAPAL : ',A.NAMA_KAPAL,'<BR>CALL SIGN : ',A.CALL_SIGN) AS 'NAMA KAPAL',
          A.TGL_TIBA AS 'TGL. TIBA', CONCAT(G.NAMA,'<BR>',DATE_FORMAT(A.TGL_UBAH_STATUS,'%d-%m-%Y %H:%i:%s')) AS STATUS,A.NO_VOY_FLIGHT
          FROM t_ubah_status A INNER JOIN reff_gudang C ON A.KD_GUDANG_ASAL=C.KD_GUDANG INNER JOIN reff_tps B ON C.KD_TPS=B.KD_TPS 
          INNER JOIN reff_gudang E ON A.KD_GUDANG_TUJUAN=E.KD_GUDANG INNER JOIN reff_tps D ON E.KD_TPS=D.KD_TPS INNER JOIN app_user F 
          ON A.ID_USER=F.ID
          LEFT JOIN reff_status G ON G.ID = A.KD_STATUS AND G.KD_TIPE_STATUS = 'UBHSTAT' 
          WHERE 1=1" . $addsql; //print_r($SQL);die();   

    $proses = array('ENTRY' => array('ADD_MODAL', "status/listdata/add", '0', '', 'icon-plus', '80'),
          'UPDATE' => array('GET',site_url()."/status/listdata/update", '1','','icon-refresh'),
          'DELETE' => array('DELETE', site_url() . "/status/execute/delete/ubahstatus", 'ALL', '', 'icon-trash'),
          'KIRIM' => array('GET_POST',site_url()."/status/execute/send_ubah_stat", 'ALL','','icon-share-alt')
          #'PRINT PERNYATAAN' => array('EXCEL', site_url() . "/plp/execute/cetak/word", '1', '100', 'icon-share-alt'),
          #'PRINT SURAT' => array('EXCEL', site_url() . "/plp/execute/cetak/excel", '1', '100', 'icon-share-alt'),
          //'PRINT' => array('PRINT', site_url() . "/status/proses_print/ubahstatus", '1', '', 'icon-printer'),
           );
      $check = (grant() == "W") ? true : false;
      $this->newtable->show_chk($check);
      if(!$check) $proses = '';
      $this->newtable->multiple_search(true);
      $this->newtable->show_search(true);
      $this->newtable->search(array(array('NO_VOY_FLIGHT','NO. VOYAGE/FLIGHT'),array('TGL_TIBA','TGL. TIBA','DATERANGE')));
      $this->newtable->action(site_url() . "/status/listdata");
      $this->newtable->detail(array('POPUP', "status/listdata/detail"));
      $this->newtable->tipe_proses('button');
      $this->newtable->hiddens(array("ID"));
      $this->newtable->keys(array("ID"));
      $this->newtable->validasi(array("ID"));
      $this->newtable->cidb($this->db);
      $this->newtable->orderby(1);
      $this->newtable->sortby("DESC");
      $this->newtable->set_formid("tblubahstatus");
      $this->newtable->set_divid("divtblubahstatus");
      $this->newtable->rowcount(10);
      $this->newtable->clear();
      $this->newtable->menu($proses);
      $tabel .= $this->newtable->generate($SQL);
      $arrdata = array("title" => $judul, "content" => $tabel);
      if ($this->input->post("ajax") || $act == "post")
        echo $tabel;
      else
        return $arrdata;
}

function autocomplete($type,$act,$get){
    $post = $this->input->post('term');
    if($type=="reff_gudang"){
        if($act=="nama"){        
          if (!$post) return;
          $addSQL = '';
          if($get == '1'){
            $addSQL .= " AND A.TIPE = '1' ";
          }else if($get == '2'){
            $addSQL .= " AND A.TIPE = '2' ";
          }

          $SQL = "SELECT A.KD_GUDANG AS KODE,CONCAT(A.NAMA_GUDANG,' - ',B.NAMA_TPS) AS NAMANYA 
          FROM reff_gudang A LEFT JOIN reff_tps B ON A.KD_TPS=B.KD_TPS 
          WHERE CONCAT(A.KD_GUDANG,' ',A.NAMA_GUDANG,' ',B.NAMA_TPS) LIKE '%".$post."%' ".$addSQL." LIMIT 5"; 
          $result = $this->db->query($SQL);
          $banyakData = $result->num_rows();
          $arrayDataTemp = array();
          if($banyakData > 0){
            foreach($result->result() as $row){
              $KODE = strtoupper($row->KODE);
              $NAMA = strtoupper($row->NAMANYA);
              $arrayDataTemp[] = array("value"=>$NAMA,"NAMA"=>$KODE);
            }
          } 
        }
        echo json_encode($arrayDataTemp);
    }  

  if($type=="reff_kapal"){
      if($act=="ship_name"){
        if (!$post) return;
        $SQL = "SELECT ID,NAMA, CALL_SIGN FROM reff_kapal WHERE CONCAT(NAMA,' ',CALL_SIGN) LIKE '%".$post."%' LIMIT 5"; //print_r($SQL);die();  
        $result = $this->db->query($SQL);
        $banyakData = $result->num_rows();
        $arrayDataTemp = array();
        if($banyakData > 0){
          foreach($result->result() as $row){
            $KODE = strtoupper($row->ID);
            $NAMA = strtoupper($row->NAMA);
            $CALLSIGN = strtoupper($row->CALL_SIGN);            
            $arrayDataTemp[] = array("value"=>$KODE,"label"=>$NAMA , "NAMA"=>$NAMA,"CALLSIGN"=>$CALLSIGN);
          }
        }
      }
      echo json_encode($arrayDataTemp);
    }
  }

function execute($type, $act, $id) {  

    $func = get_instance();
    $func->load->model("m_main", "main", true);
    $success = 0;
    $error = 0;
    $KD_TPS = $this->newsession->userdata('KD_TPS');
    $KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
    $KD_KPBC = $this->newsession->userdata('KD_KPBC');
    
// for save new record data \\
    if ($type == "save") {
      if($this->input->post('GUDANG_ASAL2') == $this->input->post('GUDANG_TUJUAN2'))
      {
        $error += 1;
        $message .= "Could not be processed data";
      }
      else
      {
        $NMKAPAL = trim($this->input->post('NAMA_KAPAL'));
        $CALL_SIGN = trim($this->input->post('CALL_SIGN'));
        $check = $this->db->query("SELECT * FROM reff_kapal WHERE NAMA ='".$NMKAPAL."'");
        $result = $check->num_rows();
        if($result < 0)
        {
          $insert = $this->db->query("INSERT INTO reff_kapal (NAMA,CALL_SIGN,CREATE_USER,CREATE_DATE) VALUES ('".$NMKAPAL."','".$CALL_SIGN."','".$this->newsession->userdata('ID')."',date('Y-m-d H:i:s'))");
        }
        $ubah= array(
          'NO_UBAH_STATUS'  =>  'ST'.date('YmdHis'),
          'TGL_UBAH_STATUS' =>  date('Y-m-d'),
          'KD_GUDANG_ASAL'  =>  validate($this->input->post('GUDANG_ASAL2')),
          'KD_GUDANG_TUJUAN'=>  validate($this->input->post('GUDANG_TUJUAN2')),
          'ID_USER'         =>  $this->newsession->userdata('ID'),
          'KD_STATUS'       => '0',
          'NAMA_KAPAL'      =>  trim(validate($this->input->post('NAMA_KAPAL'))),
          'CALL_SIGN'       =>  trim(validate($this->input->post('CALL_SIGN'))),
          'NO_VOY_FLIGHT'   =>  trim(validate($this->input->post('NO_VOYAGE'))),
          'TGL_TIBA'        =>  validate(date_input($this->input->post('TGL_TIBA'))),
          'NO_BC11'         =>  trim(validate($this->input->post('NO_BC11'))),
          'TGL_BC11'        =>  validate(date_input($this->input->post('TGL_BC11'))),
          'WK_REKAM'        =>  date('Y-m-d H:i:s')
          );
        $run = $this->db->insert('t_ubah_status',$ubah);

        if (!$run) {
        $error += 1;
        $message .= "Could not be processed data";
        }
                          
      }

      if($error == 0){
        $func->main->get_log("add","t_ubah_status");
        echo "MSG#OK#Successfully to be processed#". site_url() . "/status/listdata";
      }
      else{
        echo "MSG#ERR#".$message."#";
      }
    } 
    else if ($type == "update") { 
      $id = $this->input->post('ID_DATA');      
      if($this->input->post('GUDANG_ASAL2') == $this->input->post('GUDANG_TUJUAN2'))
      {
        $error += 1;
        $message .= "Could not be processed data";
      }      
      else
      {
        $NMKAPAL = trim($this->input->post('NAMA_KAPAL'));
        $CALL_SIGN = trim($this->input->post('CALL_SIGN'));
        $check = $this->db->query("SELECT * FROM reff_kapal WHERE NAMA='$NMKAPAL'");
        $result = $check->num_rows();
        if($result < 0)
        {
          $insert = $this->db->query("INSERT INTO reff_kapal (NAMA,CALL_SIGN,CREATE_USER,CREATE_DATE) VALUES ('".$NMKAPAL."','".$CALL_SIGN."','".$this->newsession->userdata('ID')."',date('Y-m-d H:i:s'))");
        }
          $ubah= array(
            'TGL_UBAH_STATUS' =>  date('Y-m-d'),
            'KD_GUDANG_ASAL'  =>  validate($this->input->post('GUDANG_ASAL2')),
            'KD_GUDANG_TUJUAN'=>  validate($this->input->post('GUDANG_TUJUAN2')),
            'ID_USER'         =>  $this->newsession->userdata('ID'),
            'KD_STATUS'       => '0',
            'NAMA_KAPAL'      =>  trim(strtoupper(validate($this->input->post('NAMA_KAPAL')))),
            'CALL_SIGN'       => trim(strtoupper(validate($this->input->post('CALL_SIGN')))),
            'NO_VOY_FLIGHT'   =>  trim(strtoupper(validate($this->input->post('NO_VOYAGE')))),
            'TGL_TIBA'        =>  validate(date_input($this->input->post('TGL_TIBA'))),
            'NO_BC11'         =>  trim(strtoupper(validate($this->input->post('NO_BC11')))),
            'TGL_BC11'        =>  validate(date_input($this->input->post('TGL_BC11'))),
            'WK_REKAM'        =>  date('Y-m-d H:i:s')
            );
         
        $this->db->where(array('ID' => $id));
        $run = $this->db->update('t_ubah_status', $ubah);
        if (!$run) {
             $error += 1;
            $message .= "Could not be processed data";
        }
      }
      if($error == 0){
        $func->main->get_log("add","t_ubah_status");
        echo "MSG#OK#Successfully to be processed#". site_url() . "/status/listdata";
      }
      else{
        echo "MSG#ERR#".$message."#";
      }
    }    
    else if ($type == "delete") {      
        foreach ($this->input->post('tb_chktblubahstatus') as $chkitem) {
        $arrchk = explode("~", $chkitem);
        $ID = $arrchk[0];
        $result = $this->db->delete('t_ubah_status', array('ID' => $ID));
          if ($result == false) {
            $error += 1;
            $message .= "Could not be processed data";
          }
        }
        if ($error == 0) {
          $func->main->get_log("delete", "t_ubah_status");
          echo "MSG#OK#Successfully to be processed#". site_url() . "/status/listdata/post";
        } else {
          echo "MSG#ERR#" . $message . "#";
        }
    }
     else if ($type == "get") {
      if ($act == "t_ubah_status") {
        $SQL = "SELECT A.NO_UBAH_STATUS,A.TGL_UBAH_STATUS,CONCAT(B.NAMA_TPS,' - ',C.NAMA_GUDANG) AS 'GUDANGASAL',CONCAT(D.NAMA_TPS,'  - ',E.NAMA_GUDANG) AS 'GUDANGTUJUAN',F.NM_LENGKAP,A.ID_USER,A.NAMA_KAPAL,A.CALL_SIGN,A.NO_BC11,A.TGL_BC11,A.NO_VOY_FLIGHT,A.TGL_TIBA, CASE WHEN A.KD_STATUS='0' THEN 'MENUNGGU' WHEN A.KD_STATUS='1' THEN 'PROSES'  ELSE 'SELESAI' END AS STATUS,A.ID,A.KD_GUDANG_ASAL,A.KD_GUDANG_TUJUAN
            FROM t_ubah_status A 
            INNER JOIN reff_gudang C ON A.KD_GUDANG_ASAL=C.KD_GUDANG 
            INNER JOIN reff_tps B ON C.KD_TPS=B.KD_TPS 
            INNER JOIN reff_gudang E ON A.KD_GUDANG_TUJUAN=E.KD_GUDANG 
            INNER JOIN reff_tps D ON E.KD_TPS=D.KD_TPS 
            INNER JOIN app_user F ON A.ID_USER=F.ID  
            WHERE A.ID = " . $this->db->escape($id);
          $result = $func->main->get_result($SQL);
          if ($result) { 
            foreach ($SQL->result_array() as $row => $value) {
              $arrdata = $value;
            }
            return $arrdata;
          } else {
            redirect(site_url(), 'refresh');
          }
      }
    }else if($type == "send_ubah_stat"){
      $sendData = true;        
        if($sendData){
          foreach($this->input->post('tb_chktblubahstatus') as $chkitem){            
            $arrchk = explode("~", $chkitem);
            $id_stat = $arrchk[0];
            $this->db->where(array('ID'=>$id_stat));
            $this->db->update('t_ubah_status',array('KD_STATUS'=>'1','TGL_UBAH_STATUS'=>date('Y-m-d H:i:s')));
          } 
        }else{
          $error += 1;
          $message = "Data gagal diproses";
        }
        if($error == 0){
          $func->main->get_log("kirim", "t_ubah_status");
          echo "MSG#OK#Data berhasil diproses#".site_url()."/status/listdata";
        }else{
          echo "MSG#ERR#".$message."#";
        }
    }
} 

 function proses_print($type, $act, $id) {  
    $func = get_instance();
    $func->load->model("m_main", "main", true);
    $data = array();
    $datadtl = array();
    $arrid = explode("~", $act);            
    $SQL = "SELECT * FROM t_ubah_status WHERE ID = " . $this->db->escape($arrid[0]); 
    $hasil = $func->main->get_result($SQL);
    if ($hasil) {
        foreach ($SQL->result_array() as $row => $value) {
            $data = $value;
        }
    }
    $returnArray = array('data' => $data );
    return $returnArray;       
  }    


































  function get_combobox($act) {
    $func = get_instance();
    $func->load->model("m_main", "main", true);
    $id = $this->input->post('id');
    $name = $this->input->post('name');
    if ($act == "port") {
      $sql = "SELECT ID, NAMA FROM reff_pelabuhan";
      $arrdata = $func->main->get_combobox($sql, "ID", "NAMA", TRUE);
      return $arrdata;
    } else if ($act == "negara") {
      $sql = "SELECT ID, UPPER(NAMA) AS NAMA FROM reff_negara ORDER BY NAMA";
      $arrdata = $func->main->get_combobox($sql, "ID", "NAMA", TRUE);
      return $arrdata;
    } else if ($act == "status_container") {
      $sql = "SELECT ID, UPPER(NAMA) AS NAMA FROM reff_cont_status ORDER BY NAMA";
      $arrdata = $func->main->get_combobox($sql, "ID", "NAMA", TRUE);
      return $arrdata;
    } else if ($act == "sarana_angkut") {
      $sql = "SELECT ID, UPPER(NAMA) AS NAMA FROM reff_sarana_angkut ORDER BY NAMA";
      $arrdata = $func->main->get_combobox($sql, "ID", "NAMA", TRUE);
      return $arrdata;
    }
  }




  function set_seri($table, $id) {
    $func = get_instance();
    $func->load->model("m_main", "main", true);
    $SQL = "SELECT IFNULL(MAX(SERI)+1,1) AS SERI 
				FROM $table 
				WHERE ID = " . $this->db->escape($id);
    $result = $func->main->get_result($SQL);
    if ($result) {
      $seri = $SQL->row()->SERI;
    }
    return $seri;
  }

  

  function stacking($act, $id) {
    $judul = "DATA BONGKAR";
    $KD_TPS = $this->newsession->userdata('KD_TPS');
    $KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
    $KD_GROUP = $this->newsession->userdata('KD_GROUP');
    if ($KD_GROUP != "SPA") {
      $addsql .= " AND B.KD_TPS = " . $this->db->escape($KD_TPS) . " AND B.KD_GUDANG = " . $this->db->escape($KD_GUDANG);
    }
    $SQL = "SELECT A.KD_REPOHDR, A.NO_MASTER_BL_AWB, A.TGL_MASTER_BL_AWB, A.NO_MASTER_BL_AWB AS 'NOMOR M BL/AWB', A.TGL_MASTER_BL_AWB AS 'TANGGAL M BL/AWB', 
			CONCAT((SELECT SUM(JUMLAH) FROM t_repokms WHERE NO_MASTER_BL_AWB = A.NO_MASTER_BL_AWB AND TGL_MASTER_BL_AWB = A.TGL_MASTER_BL_AWB),' Colly ','<br>',(SELECT SUM(BRUTO) FROM t_repokms WHERE NO_MASTER_BL_AWB = A.NO_MASTER_BL_AWB AND TGL_MASTER_BL_AWB = A.TGL_MASTER_BL_AWB),' Kg') AS JUMLAH, A.NO_POS_BC11 AS 'NO POS/PU', B.NM_ANGKUT AS 'PESAWAT',
			CONCAT(B.NO_VOY_FLIGHT,'<br>', B.TGL_TIBA) AS FLIGHT
			FROM t_repokms A
			INNER JOIN t_repohdr B ON A.KD_REPOHDR = B.ID
			WHERE 1=1 AND A.FL_PLP = 'N'" . $addsql;#A.NO_MASTER_BL_AWB, A.TGL_MASTER_BL_AWB GROUP BY A.NO_MASTER_BL_AWB, A.TGL_MASTER_BL_AWB
    $proses = array('SELECT' => array('GET', site_url() . "/plp/pengajuan/add", '1', '', 'icon-check', '', '1'));
    $this->newtable->show_chk(true);
    $this->newtable->multiple_search(true);
    $this->newtable->show_search(true);
    $this->newtable->search(array(array('A.NO_MASTER_BL_AWB', 'NOMOR M BL/AWB'), array('A.TGL_MASTER_BL_AWB', 'TANGGAL M BL/AWB', 'DATERANGE'), array('B.NO_VOY_FLIGHT', 'NO. FLIGHT'), array('B.TGL_TIBA', 'TGL. TIBA', 'DATERANGE')));
    $this->newtable->action(site_url() . "/plp/stacking");
    $this->newtable->tipe_proses('button');
    $this->newtable->hiddens(array("KD_REPOHDR","NO_MASTER_BL_AWB","TGL_MASTER_BL_AWB"));
    $this->newtable->keys(array("KD_REPOHDR","NO_MASTER_BL_AWB","TGL_MASTER_BL_AWB"));
    $this->newtable->cidb($this->db);
    $this->newtable->orderby(1);
    $this->newtable->sortby("DESC");
    $this->newtable->groupby(array("A.NO_MASTER_BL_AWB", "A.TGL_MASTER_BL_AWB"));
    $this->newtable->set_formid("tblstacking");
    $this->newtable->set_divid("divtblstacking");
    $this->newtable->rowcount(10);
    $this->newtable->clear();
    $this->newtable->menu($proses);
    $tabel .= $this->newtable->generate($SQL);
    $arrdata = array("title" => $judul, "content" => $tabel);
    if ($this->input->post("ajax") || $act == "post")
      return $tabel;
    else
      return $arrdata;
  }

  function table_kemasan($act, $id) {
    $judul = "&nbsp;";
    $KD_TPS = $this->newsession->userdata('KD_TPS');
    $KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
    $KD_GROUP = $this->newsession->userdata('KD_GROUP');
	$arrid = explode("~", $id);
    $SQL = "SELECT A.KD_KEMASAN AS KEMASAN, A.JUMLAH, CONCAT(IFNULL(A.NO_MASTER_BL_AWB,'-'),'<div>',DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'</div>' ) AS 'M BL/AWB', CONCAT(IFNULL(A.NO_BL_AWB,'-'),'<div>',DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'</div>' ) AS 'H BL/AWB',
				A.BRUTO, C.NAMA AS CONISGNEE, D.NAMA AS SHIPPER ,A.KD_REPOHDR, A.SERI 
				FROM t_repokms A 
				LEFT JOIN t_repohdr B ON B.ID=A.KD_REPOHDR 
				LEFT JOIN t_organisasi C ON C.ID=A.KD_ORG_CONSIGNEE 
				LEFT JOIN t_organisasi D ON D.ID=A.KD_ORG_SHIPPER
				WHERE A.KD_REPOHDR = " . $this->db->escape($arrid[0]) . " AND A.NO_MASTER_BL_AWB= ".$this->db->escape($arrid[1]). " AND A.TGL_MASTER_BL_AWB= ".$this->db->escape($arrid[2]);
    $this->newtable->show_chk(false);
    $this->newtable->multiple_search(false);
    $this->newtable->checked_val($id);
    $this->newtable->show_search(false);
    $this->newtable->search(array(array('A.KD_KEMASAN', 'KODE KEMASAN'), array('A.NO_BL_AWB', 'NO. BL/AWB')));
    $this->newtable->action(site_url() . "/plp/table_kemasan/" . $act . "/" . $id);
    $this->newtable->tipe_proses('button');
    $this->newtable->hiddens(array("KD_REPOHDR", "SERI"));
    $this->newtable->keys(array("KD_REPOHDR", "SERI"));
    $this->newtable->cidb($this->db);
    $this->newtable->orderby(1);
    $this->newtable->sortby("DESC");
    $this->newtable->set_formid("tblkemasan");
    $this->newtable->set_divid("divtblkemasan");
    $this->newtable->rowcount(10);
    $this->newtable->clear();
    $this->newtable->menu($proses);
    $tabel .= $this->newtable->generate($SQL);
    $arrdata = array("title" => $judul, "content" => $tabel);
    if ($this->input->post("ajax") || $act == "post")
      echo $tabel;
    else
      return $arrdata;
  }

  function table_kemasan_plp($act, $id) {
    $arrid = explode("~", $id);
    $judul = "DATA KEMASAN PLP";
    $SQL = "SELECT CONCAT(func_name(A.KD_KEMASAN,'KEMASAN'),' [',A.KD_KEMASAN,']') AS KEMASAN, 
				A.JML_KMS AS JUMLAH, CONCAT(IFNULL(A.NO_MASTER_BL_AWB,'-'),'<div>',DATE_FORMAT(A.TGL_MASTER_BL_AWB,'%d-%m-%Y'),'</div>' ) AS 'M BL/AWB', CONCAT(IFNULL(A.NO_BL_AWB,'-'),'<div>',DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'</div>' ) AS 'H BL/AWB'
				FROM t_request_plp_kms A
				WHERE A.ID = " . $this->db->escape($arrid[0]);
    $this->newtable->show_chk(false);
    $this->newtable->multiple_search(false);
    $this->newtable->show_search(true);
    $this->newtable->search(array(array('A.KD_KEMASAN', 'KODE KEMASAN'), array('A.NO_BL_AWB', 'NO. BL/AWB')));
    $this->newtable->action(site_url() . "/plp/table_kemasan_plp/" . $act . "/" . $id);
    $this->newtable->tipe_proses('button');
    $this->newtable->hiddens(array("ID", "SERI"));
    $this->newtable->keys(array("ID", "SERI"));
    $this->newtable->cidb($this->db);
    $this->newtable->orderby(1);
    $this->newtable->sortby("DESC");
    $this->newtable->set_formid("tblkemasanplp");
    $this->newtable->set_divid("divtblkemasanplp");
    $this->newtable->rowcount(10);
    $this->newtable->clear();
    $this->newtable->menu($proses);
    $tabel .= $this->newtable->generate($SQL);
    $arrdata = array("title" => $judul, "content" => $tabel);
    if ($this->input->post("ajax") || $act == "post")
      echo $tabel;
    else
      return $arrdata;
  }
  
   function table_kemasan_repo($act, $id) {
    $arrid = explode("~", $id);
    $judul = "DATA KEMASAN PLP";
    $SQL = "SELECT CONCAT(func_name(A.KD_KEMASAN,'KEMASAN'),' [',A.KD_KEMASAN,']') AS KEMASAN, 
				A.JML_KMS AS JUMLAH, A.BRUTO, CONCAT(IFNULL(A.NO_BL_AWB,'-'),'<div>',DATE_FORMAT(A.TGL_BL_AWB,'%d-%m-%Y'),'</div>' ) AS 'H BL/AWB'
				FROM t_repo_request_plp_kms A
				WHERE A.ID = " . $this->db->escape($arrid[0]); //print_r($SQL);die();
    $this->newtable->show_chk(false);
    $this->newtable->multiple_search(false);
    $this->newtable->checked_val($id);
    $this->newtable->show_search(false);
    $this->newtable->search(array(array('A.KD_KEMASAN', 'KODE KEMASAN'), array('A.NO_BL_AWB', 'NO. BL/AWB')));
    $this->newtable->action(site_url() . "/plp/table_kemasan_repo/" . $act . "/" . $id);
    $this->newtable->tipe_proses('button');
    $this->newtable->hiddens(array("ID", "NO_BL_AWB"));
    $this->newtable->keys(array("ID", "NO_BL_AWB"));
    $this->newtable->cidb($this->db);
    $this->newtable->orderby(1);
    $this->newtable->sortby("DESC");
    $this->newtable->set_formid("tblkemasanrepo");
    $this->newtable->set_divid("divtblkemasanrepo");
    $this->newtable->rowcount(10);
    $this->newtable->clear();
    $this->newtable->menu($proses);
    $tabel .= $this->newtable->generate($SQL);
    $arrdata = array("title" => $judul, "content" => $tabel);
    if ($this->input->post("ajax") || $act == "post")
      echo $tabel;
    else
      return $arrdata;
  }
  
  function get_plp_excel($id) {
    $func = get_instance();
    $func->load->model("m_main", "main", true);

    $SQL = "SELECT A.NO_SURAT, A.TGL_SURAT, func_name(A.KD_TPS_ASAL,'TPS') AS TPS_ASAL, A.KD_GUDANG_ASAL AS GUDANG_ASAL,
            func_name(A.KD_TPS_TUJUAN,'TPS') AS TPS_TUJUAN, KD_GUDANG_TUJUAN AS GUDANG_TUJUAN, A.NO_BC11, A.TGL_BC11,
            A.NM_PEMOHON, B.NAMA AS ALASAN_PLP, A.ID
            FROM t_repo_request_plp_hdr A
			INNER JOIN reff_alasan_plp B ON B.ID = A.KD_ALASAN_PLP
            WHERE A.ID = " . $this->db->escape($id);
    $result = $func->main->get_result($SQL);
    if ($result) {
      foreach ($SQL->result_array() as $row) {
        $IDPLP = $row['ID'];
        $NOPLP = $row['NO_SURAT'];
        $NOBC11 = $row['NO_BC11'];
        $NOPOSBC11 = $row['NO_BC11'];
        $TGLBC11 = $row['TGL_BC11'];
        $NAMATPSASAL = $row['TPS_ASAL'];
        $KODEGUDANGASAL = $row['GUDANG_ASAL'];
        $NAMATPSTUJUAN = $row['TPS_TUJUAN'];
        $KODEGUDANGTUJUAN = $row['GUDANG_TUJUAN'];
        $NAMAPEMOHON = $row['NM_PEMOHON'];
        $ALASAN_PLP = $row['ALASAN_PLP'];
      }
    } else {
      exit();
    }
    $this->load->library('newphpexcel');
    $this->newphpexcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(9);

    $this->newphpexcel->setActiveSheetIndex(0);
 
    $this->newphpexcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);

    $this->newphpexcel->getActiveSheet()->getPageMargins()->setTop(0.6);
    $this->newphpexcel->getActiveSheet()->getPageMargins()->setRight(0);
    $this->newphpexcel->getActiveSheet()->getPageMargins()->setLeft(0.4);
    $this->newphpexcel->getActiveSheet()->getPageMargins()->setBottom(0);

    $style = array('alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    ));
    $styler = array('alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
    ));

    $this->newphpexcel->getActiveSheet()->getStyle("A15:F17")->applyFromArray($style);
    $this->newphpexcel->getActiveSheet()->getStyle("A14:G14")->applyFromArray($styler);
    $this->newphpexcel->getActiveSheet()->getStyle("A19:F21")->applyFromArray($styler);
    $this->newphpexcel->getActiveSheet()->getStyle("F26:F29")->applyFromArray($style);

    $this->newphpexcel->getActiveSheet()->getColumnDimension('A')->setWidth(5.29);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('B')->setWidth(7.29);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('C')->setWidth(1.57);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('D')->setWidth(7);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('E')->setWidth(7);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('F')->setWidth(5.58);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('H')->setWidth(3);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('I')->setWidth(14.50);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('J')->setWidth(1.3);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('M')->setWidth(7);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
    $this->newphpexcel->getActiveSheet()->getColumnDimension('O')->setWidth(10.50);
    $this->newphpexcel->getActiveSheet()->getRowDimension('15')->setRowHeight(14);
    $this->newphpexcel->getActiveSheet()->getRowDimension('16')->setRowHeight(14);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A4', 'Nomor');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C4', ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D4', $NOPLP);
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A5', 'Lampiran');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C5', ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D5', '  Surat Pernyataan,Fotocopy Mawb, Hawb, Invoice, Cargo Manifes, Do');

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A6', 'Hal');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C6', ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D6', ' Permohonan Pindah Lokasi Penimbunan');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A8', 'Yth. Kepala KPU Bea dan Cukai Tipe C Soekarno-Hatta');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('B9', 'u.p. Kepala Bidang Pelayanan dan Fasilitas Pabean dan Cukai II');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('B11', 'Dengan ini kami mengajukan permohonan Pindah Lokasi Penimbunan barang import yang belum');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A12', 'diselesaikan kewajiban pabeannya (PLP) sebagai berikut:                                                            ');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A14', 'BC 1.1 Nomor  :');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D14', $NOBC11);
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('F14', 'Pos:');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('G14', $NOPOSBC11);
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I14', 'Tanggal');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J14', ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('K14', $TGLBC11);

    $this->newphpexcel->getActiveSheet()->mergeCells('D4:H4');
    $this->newphpexcel->getActiveSheet()->mergeCells('B16:D16');
    $this->newphpexcel->getActiveSheet()->mergeCells('D5:K5');
    $this->newphpexcel->getActiveSheet()->mergeCells('D6:I6');
    $this->newphpexcel->getActiveSheet()->mergeCells('E16:G16');
    $this->newphpexcel->getActiveSheet()->mergeCells('B15:G15');
    $this->newphpexcel->getActiveSheet()->mergeCells('A12:I12');
    $this->newphpexcel->getActiveSheet()->mergeCells('A14:C14');
    $this->newphpexcel->getActiveSheet()->mergeCells('L15:M15');
    $this->newphpexcel->getActiveSheet()->mergeCells('L16:M16');
    $this->newphpexcel->getActiveSheet()->mergeCells('H15:K15');
    $this->newphpexcel->getActiveSheet()->mergeCells('H16:J16');

    $this->newphpexcel->getActiveSheet()->getStyle('A15:M16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $this->newphpexcel->getActiveSheet()->getStyle('F14:F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
    $this->newphpexcel->getActiveSheet()->getStyle('I14:I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $this->newphpexcel->setActiveSheetIndex(0)
            ->setCellValue('A15', 'No.')
            ->setCellValue('A16', 'Urut')
            ->setCellValue('B15', 'Kemasan')
            ->setCellValue('B16', 'Jenis')
            ->setCellValue('E16', 'Jumlah')
            ->setCellValue('H15', 'Dokumen AWB / BL')
            ->setCellValue('H16', 'Nomor')
            ->setCellValue('K16', 'Tanggal')
            ->setCellValue('L15', 'Keputusan Pejabat')
            ->setCellValue('L16', 'BC');
    $this->newphpexcel->set_wrap(array('B', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'));
    $no = 1;
    $rec = 17;

    $SQL_DETIL = "SELECT func_name(B.KD_KEMASAN,'KEMASAN') AS KEMASAN, B.JML_KMS, B.NO_BL_AWB, B.TGL_BL_AWB ,B.KD_KEMASAN AS JENISKEMAS
                  FROM t_repo_request_plp_kms B
                  LEFT JOIN t_repo_request_plp_hdr A ON B.ID = A.ID
                  WHERE A.ID = " . $this->db->escape($IDPLP);
    $result_dtl = $func->main->get_result($SQL_DETIL);
    if ($result_dtl) {
      foreach ($SQL_DETIL->result_array() as $rowdtl) {

        $this->newphpexcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $rec, $no)
                ->setCellValue('B' . $rec, $rowdtl["JENISKEMAS"])
                ->setCellValue('E' . $rec, $rowdtl["JML_KMS"] . " " . $rowdtl["KEMASAN"])
                ->setCellValue('H' . $rec, $rowdtl["NO_BL_AWB"])
                ->setCellValue('K' . $rec, $rowdtl["TGL_BL_AWB"])
                ->setCellValue('L' . $rec, 'Disetujui / Ditolak* ');

        $this->newphpexcel->set_detilstyle(array('A' . $rec, 'B' . $rec, 'C' . $rec, 'D' . $rec, 'E' . $rec, 'F' . $rec, 'G' . $rec, 'H' . $rec, 'I' . $rec, 'J' . $rec, 'K' . $rec, 'L' . $rec, 'M' . $rec));
        $this->newphpexcel->getActiveSheet()->mergeCells('L' . $rec . ':M' . $rec);
        $this->newphpexcel->getActiveSheet()->mergeCells('H' . $rec . ':J' . $rec);
        $this->newphpexcel->getActiveSheet()->mergeCells('E' . $rec . ':G' . $rec);
        $this->newphpexcel->getActiveSheet()->mergeCells('B' . $rec . ':D' . $rec);
        $this->newphpexcel->getActiveSheet()->getStyle('A' . $rec . ':M' . $rec)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $rec++;
        $no++;
      }
    } else {
      $this->newphpexcel->getActiveSheet()->mergeCells('A17:M18');
      $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A17', 'Data Tidak Ditemukan');
      $this->newphpexcel->set_detilstyle(array('A17'));
    }

    $BStyle = array('borders' => array('outline'
            => array('style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    );

    $this->newphpexcel->getActiveSheet()->getStyle('A15:A16')->applyFromArray($BStyle);
    $this->newphpexcel->getActiveSheet()->getStyle('B15:G15')->applyFromArray($BStyle);
    $this->newphpexcel->getActiveSheet()->getStyle('E16:G16')->applyFromArray($BStyle);
    $this->newphpexcel->getActiveSheet()->getStyle('H15:K15')->applyFromArray($BStyle);
    $this->newphpexcel->getActiveSheet()->getStyle('L15:M16')->applyFromArray($BStyle);
    $this->newphpexcel->getActiveSheet()->getStyle('H15:J16')->applyFromArray($BStyle);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 1), 'TPS Asal');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 1) . ':B' . ($rec + 1));
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C' . ($rec + 1), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 1), $NAMATPSASAL);
    $this->newphpexcel->getActiveSheet()->mergeCells('D' . ($rec + 1) . ':G' . ($rec + 1));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 2), 'TPS Tujuan');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 2) . ':B' . ($rec + 2));
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C' . ($rec + 2), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 2), $NAMATPSTUJUAN);
    $this->newphpexcel->getActiveSheet()->mergeCells('D' . ($rec + 2) . ':G' . ($rec + 2));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 3), 'Alasan');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 3) . ':B' . ($rec + 3));
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C' . ($rec + 3), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 3), $ALASAN_PLP);
    $this->newphpexcel->getActiveSheet()->mergeCells('D' . ($rec + 3) . ':K' . ($rec + 3));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 5), 'Demikian kami sampaikan untuk dapat dipertimbangkan.                  
');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 5) . ':I' . ($rec + 5));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 1), 'kode TPS');
    $this->newphpexcel->getActiveSheet()->getStyle('I' . ($rec + 1) . ':I' . ($rec + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 1), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('K' . ($rec + 1), $KODEGUDANGASAL);
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 2), 'kode TPS');
    $this->newphpexcel->getActiveSheet()->getStyle('I' . ($rec + 2) . ':I' . ($rec + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 2), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('K' . ($rec + 2), $KODEGUDANGTUJUAN);
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('L' . ($rec + 1), 'YOR/SOR : -   %');
    $this->newphpexcel->getActiveSheet()->mergeCells('L' . ($rec + 1) . ':M' . ($rec + 1));
    $this->newphpexcel->getActiveSheet()->getStyle('L' . ($rec + 1) . ':M' . ($rec + 1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('L' . ($rec + 2), 'YOR/SOR : -   %');
    $this->newphpexcel->getActiveSheet()->mergeCells('L' . ($rec + 2) . ':M' . ($rec + 2));
    $this->newphpexcel->getActiveSheet()->getStyle('L' . ($rec + 2) . ':M' . ($rec + 2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('K' . ($rec + 7), 'Pemohon,');
    $this->newphpexcel->getActiveSheet()->getStyle('K' . ($rec + 7) . ':K' . ($rec + 7))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('K' . ($rec + 12), '( ' . $NAMAPEMOHON . ' )');
    $this->newphpexcel->getActiveSheet()->getStyle('K' . ($rec + 12) . ':K' . ($rec + 12))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 14), 'Keputusan Pejabat Bea dan Cukai :');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 14) . ':F' . ($rec + 14));
    $this->newphpexcel->getActiveSheet()->getRowDimension('A' . ($rec + 14) . ':F' . ($rec + 14))->setRowHeight(100);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 15), 'Nomor');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 15) . ':B' . ($rec + 15));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C' . ($rec + 15), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 15), '....................../PLP/2016');
    $this->newphpexcel->getActiveSheet()->mergeCells('D' . ($rec + 15) . ':F' . ($rec + 15));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 16), 'Tanggal');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 16) . ':B' . ($rec + 16));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('C' . ($rec + 16), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('E' . ($rec + 16), '      Maret 2016');
    $this->newphpexcel->getActiveSheet()->mergeCells('E' . ($rec + 16) . ':G' . ($rec + 16));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 18), 'a.n Kepala Kantor,');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 18) . ':D' . ($rec + 18));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 19), '      Kepala Bidang PFPC II');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 19) . ':E' . ($rec + 19));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 20), '      u.b.');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 20) . ':B' . ($rec + 20));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 21), '      Kepala Seksi Administrasi Manifest');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 21) . ':F' . ($rec + 21));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 26), '     Misnawi');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 26) . ':B' . ($rec + 26));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 27), '     NIP. 19750514 199603 1 002');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 27) . ':E' . ($rec + 27));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 29), 'Pengeluaran dari TPS Asal');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 29) . ':E' . ($rec + 29));
    $this->newphpexcel->getActiveSheet()->getStyle('A' . ($rec + 29) . ':G' . ($rec + 41))->applyFromArray($BStyle);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 30), 'Tanggal');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 30) . ':B' . ($rec + 30));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 31), 'Pukul');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 31) . ':B' . ($rec + 31));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 32), 'No. Segel');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 32) . ':B' . ($rec + 32));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 30), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 31), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 32), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 34), 'Pejabat Bea dan Cukai :');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 34) . ':E' . ($rec + 34));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 35), 'Nama');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 35) . ':B' . ($rec + 35));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 36), 'NIP');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 37), 'Tanda Tangan');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 37) . ':C' . ($rec + 37));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 35), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 36), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('D' . ($rec + 37), ':');

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 29), 'Pemasukan ke TPS Tujuan');
    $this->newphpexcel->getActiveSheet()->mergeCells('I' . ($rec + 29) . ':K' . ($rec + 29));
    $this->newphpexcel->getActiveSheet()->getStyle('I' . ($rec + 29) . ':L' . ($rec + 41))->applyFromArray($BStyle);

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 30), 'Tanggal');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 31), 'Pukul');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 30), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 31), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 34), 'Pejabat Bea dan Cukai :');
    $this->newphpexcel->getActiveSheet()->mergeCells('I' . ($rec + 34) . ':K' . ($rec + 34));

    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 35), 'Nama');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 36), 'NIP');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('I' . ($rec + 37), 'Tanda Tangan');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 35), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 36), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('J' . ($rec + 37), ':');
    $this->newphpexcel->setActiveSheetIndex(0)->setCellValue('A' . ($rec + 42), '*) Coret yang tidak perlu / diisi oleh Pejabat Bea dan Cukai              
');
    $this->newphpexcel->getActiveSheet()->mergeCells('A' . ($rec + 42) . ':I' . ($rec + 42));
    $this->newphpexcel->getActiveSheet()->getStyle('A' . ($rec + 42) . ':I' . ($rec + 42))->getFont()->setName('Arial')->setSize(7);

    ob_clean();
    $file = "Lampiran_".str_replace('/', '', $IDPLP) . ".xls";
    header("Content-type: application/x-msdownload");
    header("Content-Disposition: attachment;filename=$file");
    header("Cache-Control: max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");
    $objWriter = PHPExcel_IOFactory::createWriter($this->newphpexcel, 'Excel5');
    $objWriter->save('php://output');
    exit();
  }
 
  
  function get_word_template($id){
      $func = get_instance();
      $func->load->model("m_main", "main", true);  
      $SQL = "SELECT A.ID, A.NM_PEMOHON, A.TGL_SURAT, A.NO_MASTER_BL_AWB, SUM(B.JML_KMS) AS JML_KMS, SUM(B.BRUTO) AS BRUTO
                FROM t_repo_request_plp_hdr A
                INNER JOIN t_repo_request_plp_kms B ON A.ID = B.ID
                WHERE A.ID = " . $this->db->escape($id);
      $result = $func->main->get_result($SQL);
      if($result){
        foreach ($SQL->result_array() as $row) {
          $row['JUMLAH'] = $row['JML_KMS']." colly";
          $tgls = $row['TGL_SURAT'];
          $row['TGLSURAT'] = $this->fungsi->FormatDateLengkap($tgls);
          $row['BERAT'] = number_format($row['BRUTO'])." Kg";
          }
      }else{
        exit();
      }
      require_once(APPPATH.'third_party/PHPWord.php');
	  
      $PHPWord = new PHPWord();
      $document = $PHPWord->loadTemplate('template/pernyataan.docx');
      $document->setValue('NAMAPENGAJU', $row['NM_PEMOHON']);
      $document->setValue('NAMATTD', $row['NM_PEMOHON']);
      $document->setValue('TGSURAT', $row['TGLSURAT']);
      $document->setValue('MAWB', $row['NO_MASTER_BL_AWB']);
      $document->setValue('JUMLAH', $row['JUMLAH']);
      $document->setValue('BRUTO', $row['BERAT']);
	  
	  $filename =  urldecode('pernyataansdv.docx');
	  $document->save('template/'.$filename);
      header("Content-type: application/vnd.ms-word");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("content-disposition: attachment;filename=$filename");
      ob_clean();
      flush();
      readfile('template/'.$filename);
      unlink('template/'.$filename);
  }
  

}
