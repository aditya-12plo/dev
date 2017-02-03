<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class M_tracking extends Model{
	
    function M_tracking() {
       parent::Model();
    }

	public function execute($type,$act,$id)
	{
		$func = get_instance();
		$func->load->model("m_main", "main", true);
		$success = 0;
		$error = 0;
		$KD_TPS = $this->newsession->userdata('KD_TPS');
		$KD_GUDANG = $this->newsession->userdata('KD_GUDANG');
		$KD_KPBC = $this->newsession->userdata('KD_KPBC');
    
		if($type == 'get'){
			if ($act == "t_cocostscont") {//print_r($id);die();
				//$arrid = explode("~", $id);//print_r($arrid);die();
				$SQL = "SELECT * FROM t_cocostscont WHERE NO_BL_AWB = " . $this->db->escape($id);//print_r($SQL);die();
				$result = $func->main->get_result($SQL);

				if ($result) { 
					/*foreach ($SQL->result_array() as $row => $value) {
						$arrdata = $value;
					}*/
					return $SQL->result();
				} else {
					echo "MSG#ERR#NO BL/AWB Yang Anda Masukan Tidak Terdaftar#";
					// return false;
				}
			}
		}
	}
}