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
		if($KD_GROUP!="SPA"){
			$addsql .= " AND A.KD_TPS_ASAL = ".$this->db->escape($KD_TPS)." AND A.KD_GUDANG_ASAL = ".$this->db->escape($KD_GUDANG);
		}
		$SQL = "SELECT IFNULL(A.REF_NUMBER,'-') AS 'REF NUMBER',
				CONCAT('NO. : ',IFNULL(A.NO_SURAT,'-'),'<BR>TGL. : ',IFNULL(DATE_FORMAT(A.TGL_SURAT,'%d-%m-%Y'),'-')) AS 'SURAT PLP',
				A.NM_ANGKUT AS 'NAMA ANGKUT', A.NO_VOY_FLIGHT AS 'VOYAGE/FLIGHT', DATE_FORMAT(A.TGL_TIBA,'%d-%m-%Y') AS 'TGL. TIBA',
				CONCAT('NO. : ',A.NO_BC11,'<BR>TGL. : ',DATE_FORMAT(A.TGL_BC11,'%d-%m-%Y')) AS BC11,
				CONCAT('YOR ASAL : ',IFNULL(A.YOR_ASAL,'-'),'<BR> YOR TUJUAN : ',IFNULL(A.YOR_TUJUAN,'-')) AS 'YOR', 
				CONCAT('TPS : ',A.KD_TPS_TUJUAN,'<BR>GUDANG : ',A.KD_GUDANG_TUJUAN) AS 'GUDANG TUJUAN', 
				CONCAT(C.NAMA,'<BR>',DATE_FORMAT(A.TGL_STATUS,'%d-%m-%Y %H:%i:%s')) AS STATUS, A.TGL_STATUS, 
				A.ID, A.KD_COCOSTSHDR, A.KD_STATUS
				FROM t_request_plp_hdr A
				LEFT JOIN reff_kapal B ON B.ID=A.KD_KAPAL
				LEFT JOIN reff_status C ON C.ID=A.KD_STATUS AND C.KD_TIPE_STATUS='PLPAJU'
				WHERE 1=1".$addsql;
		/*
		$proses = array('ENTRY'	  => array('MODAL',"plp/pengajuan_discharge", '','','glyphicon glyphicon-plus-sign'),
						'UPDATE'  => array('GET',site_url()."/plp/pengajuan_plp/update", '1','100:500','glyphicon glyphicon-edit'),
						'DELETE'  => array('DELETE',"execute/process/delete/pengajuan_plp", '1','100','glyphicon glyphicon-remove-circle'),
						'PROCESS' => array('POST',"execute/process/update/send_pengajuan_plp", '1','100','glyphicon glyphicon-send'),
						'DETAIL'  => array('MODAL',"plp/pengajuan_plp/detail", '1','','glyphicon glyphicon-zoom-in'));
	*/
		$this->newtable->multiple_search(true);
		$this->newtable->show_chk($check);
		$this->newtable->show_menu($check);
		$this->newtable->show_search(true);
		$this->newtable->search(array(array('A.NO_SURAT','NO. SURAT'),array('A.REF_NUMBER','REF NUMBER'),array('A.NM_ANGKUT','NAMA ANGKUT'),array('A.TGL_TIBA','TGL. TIBA','DATERANGE')));
		$this->newtable->action(site_url() . "/ppbarang/listdata");
		if($check) $this->newtable->detail(array('POPUP',"ppbarang/listdata/detail"));
		$this->newtable->tipe_proses('button');
		$this->newtable->hiddens(array("KD_COCOSTSHDR","ID","KD_STATUS","TGL_STATUS"));
		$this->newtable->keys(array("KD_COCOSTSHDR","ID"));
		$this->newtable->validasi(array("KD_STATUS"));
		$this->newtable->cidb($this->db);
		$this->newtable->orderby(10);
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
if($act == 't_request_plp_hdr')
{
        $SQL = "SELECT * FROM t_request_plp_hdr A LEFT JOIN reff_kapal B ON B.ID=A.KD_KAPAL
				LEFT JOIN reff_status C ON C.ID=A.KD_STATUS WHERE A.ID = " . $this->db->escape($id);
       // print_r($SQL);die();
        $result = $func->main->get_result($SQL);
        if ($result) { 
          foreach ($SQL->result_array() as $row => $value) {
            $arrdata = $value;
          }
          return $arrdata;
        } 
        else {
          redirect(site_url(), 'refresh');
        }
}



}
// for detail

}


}