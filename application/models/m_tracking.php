<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class M_tracking extends Model {
  function M_tracking() {
    parent::Model();
  }

  function cek($act, $id) {
    $this->newtable->breadcrumb('Home', site_url());
    $this->newtable->breadcrumb('Tracking', 'javascript:void(0)');
    $data['title'] = 'DATA TRACKING CONTAINER';
    $judul = "TRACKING";
    //$KD_TPS = $this->newsession->userdata('KD_TPS');
    $KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
    $KD_GROUP = $this->newsession->userdata('KD_GROUP');
    $KD_KPBC = $this->newsession->userdata('KD_KPBC');
    $addsql = '';
    if ($KD_GROUP == "USER") {
      $addsql .= " AND   A.KD_GUDANG_ASAL = " . $this->db->escape($KD_GUDANG);
    }else if($KD_GROUP == "CONS"){
      $addsql .= " AND C.ID = '".$this->newsession->userdata('ID')."' ";
    }
    $SQL = "SELECT A.NO_BL_AWB AS 'NO BL/AWB', A.JUMLAH AS 'JUMLAH KEMASAN', func_name(IFNULL(A.KD_KEMASAN,'-'),'KEMASAN') AS 'JENIS KEMASAN',
            A.NO_CONT_ASAL AS 'CONTAINER ASAL', CONCAT('NAMA KAPAL : ',B.NM_ANGKUT,'<BR>CALL SIGN : ',B.CALL_SIGN) AS 'NAMA KAPAL', B.NO_VOY_FLIGHT AS 'NO VOYAGE',
            CASE WHEN A.WK_OUT IS NOT NULL THEN CONCAT('GATE OUT : ',DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s'))
            ELSE CONCAT('GATE IN : ',DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s'),'<BR>TGL TIBA : ',DATE_FORMAT(IFNULL(B.TGL_TIBA,'-'),'%d-%m-%Y')) END AS 'STATUS',
            A.ID FROM t_cocostskms A	INNER JOIN t_cocostshdr B ON A.ID=B.ID WHERE 1=1" . $addsql;
     /*$proses = array('ENTRY' => array('ADD_MODAL', "status/listdata/add", '0', '', 'icon-plus', '80'),
          'UPDATE' => array('GET',site_url()."/status/listdata/update", '1','','icon-refresh'),
          'DELETE' => array('DELETE', site_url() . "/status/execute/delete/ubahstatus", 'ALL', '', 'icon-trash'),
          'KIRIM' => array('GET_POST',site_url()."/status/execute/send_ubah_stat", 'ALL','','icon-share-alt')
          #'PRINT PERNYATAAN' => array('EXCEL', site_url() . "/plp/execute/cetak/word", '1', '100', 'icon-share-alt'),
          #'PRINT SURAT' => array('EXCEL', site_url() . "/plp/execute/cetak/excel", '1', '100', 'icon-share-alt'),
          //'PRINT' => array('PRINT', site_url() . "/status/proses_print/ubahstatus", '1', '', 'icon-printer'),
        );*/
    $check = (grant() == "W") ? true : false;
    $this->newtable_edit->show_chk(FALSE);
    if(!$check) $proses = '';
    $this->newtable_edit->multiple_search(true);
    $this->newtable_edit->show_search(true);
    $this->newtable_edit->search(array(array('A.NO_BL_AWB','NO. BL/AWB')));
    $this->newtable_edit->action(site_url() . "/tracking/cek");
    $this->newtable_edit->detail(array('POPUP', "tracking/cek/detail"));
    $this->newtable_edit->tipe_proses('button');
    $this->newtable_edit->hiddens(array("ID"));
    $this->newtable_edit->keys(array("ID"));
    $this->newtable_edit->validasi(array("ID"));
    $this->newtable_edit->cidb($this->db);
    $this->newtable_edit->orderby(1);
    $this->newtable_edit->sortby("DESC");
    $this->newtable_edit->set_formid("tbltracking");
    $this->newtable_edit->set_divid("divtbltracking");
    $this->newtable_edit->rowcount(10);
    $this->newtable_edit->clear();
    $this->newtable_edit->menu($proses);
    $tabel .= $this->newtable_edit->generate($SQL);
    $arrdata = array("title" => $judul, "content" => $tabel);
    if ($this->input->post("ajax") || $act == "post")
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
      if ($act == "t_cocostskms") {
        $SQL = "SELECT A.NO_BL_AWB AS 'NO BL/AWB', A.JUMLAH AS 'JUMLAH KEMASAN', func_name(IFNULL(A.KD_KEMASAN,'-'),'KEMASAN') AS 'JENIS KEMASAN',
                A.NO_CONT_ASAL AS 'CONTAINER ASAL', B.NM_ANGKUT AS 'NAMA KAPAL', B.CALL_SIGN AS 'CALL SIGN', B.NO_VOY_FLIGHT AS 'NO VOYAGE',
                DATE_FORMAT(B.TGL_TIBA,'%d-%m-%Y') AS 'TGL. TIBA', DATE_FORMAT(IFNULL(A.WK_OUT,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE OUT',
                DATE_FORMAT(IFNULL(A.WK_IN,'-'),'%d-%m-%Y %H:%i:%s') AS 'GATE IN'
                FROM t_cocostskms A	INNER JOIN t_cocostshdr B ON A.ID=B.ID
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
}
