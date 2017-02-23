<?php

ob_start();
// call library
require_once ('config.php' );
require_once ($CONF['root.dir'] . 'Libraries/nusoap/nusoap.php' );
require_once ($CONF['root.dir'] . 'Libraries/xml2array.php' );

// create instance
$server = new soap_server();

// initialize WSDL support
$server->configureWSDL('TPSOnline', 'urn:TPSOnlinewsdl');

// place schema at namespace with prefix tns
$server->wsdl->schemaTargetNamespace = 'urn:TPSOnlinewsdl';
$server->register('receivePLP', // method name
        array('string' => 'xsd:string', 'string0' => 'xsd:string', 'string1' => 'xsd:string'),
        array('return' => 'xsd:string'), // output
        'urn:receivePLPwsdl', // namespace
        'urn:TPSOnline', // soapaction
        'rpc', // style
        'encoded', // use
        'Receive PLP'// documentation
);

$server->register('sendresponPLPasal', // method name
        array('string' => 'xsd:string', 'string0' => 'xsd:string', 'string1' => 'xsd:string'),
        array('return' => 'xsd:string'), // output
        'urn:sendresponPLPasalwsdl', // namespace
        'urn:TPSOnline', // soapaction
        'rpc', // style
        'encoded', // use
        'send respon PLP Asal'// documentation
);

$server->register('sendresponPLPtujuan', // method name
        array('string' => 'xsd:string', 'string0' => 'xsd:string', 'string1' => 'xsd:string'),
        array('return' => 'xsd:string'), // output
        'urn:sendresponPLPtujuanwsdl', // namespace
        'urn:TPSOnline', // soapaction
        'rpc', // style
        'encoded', // use
        'send respon PLP Tujuan'// documentation
);

$server->register('receivebatalPLP', // method name
        array('string' => 'xsd:string', 'string0' => 'xsd:string', 'string1' => 'xsd:string'),
        array('return' => 'xsd:string'), // output
        'urn:receivebatalPLPwsdl', // namespace
        'urn:TPSOnline', // soapaction
        'rpc', // style
        'encoded', // use
        'Receive Batal PLP'// documentation
);

function receivePLP($string, $string0, $string1) {
    global $CONF, $conn;
    $conn->connect();
    $userName = $string;
    $password = $string0;
    $xml = str_replace('&', ' ', $string1);

    $IDLogServices = insertLogServices($userName, $xml, '', '', 'ReceivedPLP');

    $checkUser = checkUser($userName, $password, $IDLogServices);
    
    //$KD_ORG_SENDER = $checkUser['kdorganisasi']; //tps asal/pengirim plp
    //$KD_ORG_RECEIVER = 2; //beacukai

    if (!$checkUser['return']) {
        $conn->disconnect();
        $return = $checkUser['message'];
        return $return;
    }


    if ($xml != '') {
        $xml = xml2ary($xml);        
        $HEADER = $xml['DOCUMENT']['_c']['LOADPLP']['_c']['HEADER']['_c'];
        $CONT = $xml['DOCUMENT']['_c']['LOADPLP']['_c']['DETIL']['_c']['CONT'];        
        $countCont = count($CONT);

		$inserthdr = "INSERT INTO t_request_plp_hdr (KD_KPBC,TIPE_DATA,KD_TPS_ASAL,REF_NUMBER,NO_SURAT,TGL_SURAT,KD_GUDANG_ASAL,KD_TPS_TUJUAN,KD_GUDANG_TUJUAN,
													 KD_ALASAN_PLP,YOR_ASAL,YOR_TUJUAN,NM_ANGKUT,NO_VOY_FLIGHT,TGL_TIBA,NO_BC11,TGL_BC11,NM_PEMOHON,TGL_STATUS) 
					  VALUES ('".$HEADER['KD_KANTOR']['_v']."','".$HEADER['TIPE_DATA']['_v']."','".$HEADER['KD_TPS_ASAL']['_v']."','".$HEADER['REF_NUMBER']['_v']."','".$HEADER['NO_SURAT']['_v']."',STR_TO_DATE('".$HEADER['TGL_SURAT']['_v']."','%d%m%Y'),'".$HEADER['GUDANG_ASAL']['_v']."','".$HEADER['KD_TPS_TUJUAN']['_v']."', '".$HEADER['GUDANG_TUJUAN']['_v']."','".$HEADER['KD_ALASAN_PLP']['_v']."', ".(int)$HEADER['YOR_ASAL']['_v'].",".(int)$HEADER['YOR_TUJUAN']['_v'].", '".$HEADER['NM_ANGKUT']['_v']."','".$HEADER['NO_VOY_FLIGHT']['_v']."',STR_TO_DATE('".$HEADER['TGL_TIBA']['_v']."','%d%m%Y'), '".$HEADER['NO_BC11']['_v']."',STR_TO_DATE('".$HEADER['TGL_BC11']['_v']."','%d%m%Y'),'".$HEADER['NM_PEMOHON']['_v']."',NOW())";
		//echo $inserthdr;die();
		$Execute = $conn->execute($inserthdr);
		$ID = mysql_insert_id();

		if($ID!=""){			
			$cont = '';
			$sqlcek = "SELECT A.ID,A.NO_UBAH_STATUS FROM t_ubah_status A INNER JOIN t_no_kontainer B ON B.NO_UBAH_STATUS=A.NO_UBAH_STATUS WHERE A.NO_VOY_FLIGHT='".$HEADER['NO_VOY_FLIGHT']['_v']."' AND A.NO_BC11='".$HEADER['NO_BC11']['_v']."' AND DATE_FORMAT(A.TGL_BC11,'%d%m%Y') = '".$HEADER['TGL_BC11']['_v']."' AND UPPER(A.NAMA_KAPAL) = '".strtoupper($HEADER['NM_ANGKUT']['_v'])."' ";
        	$Querycek = $conn->query($sqlcek);
        	$NO_UBAH_STATUS = '';
			if ($Querycek->size() > 0) {
				$Querycek->next();
				$IDCEK = $Querycek->get("ID");
				$NO_UBAH_STATUS = $Querycek->get("NO_UBAH_STATUS");
				$sqlupdate = "UPDATE t_ubah_status SET KD_STATUS = '400', TGL_UBAH_STATUS = NOW() WHERE ID = ".$IDCEK;
				$Executupdate = $conn->execute($sqlupdate); 
			}	

			if ($countCont > 1) {
	            for ($c = 0; $c < $countCont; $c++) {	                
	                $insertdtl = "INSERT INTO t_request_plp_cont (ID,NO_CONT,KD_CONT_UKURAN) VALUES (".$ID.",'".$CONT[$c]['_c']['NO_CONT']['_v']."','".$CONT[$c]['_c']['UK_CONT']['_v']."')";
	                $Executedtl = $conn->execute($insertdtl);              
	                if($Executedtl){
	                	$cont .='<CONT>'.$CONT[$c]['_c']['NO_CONT']['_v'].'</CONT>';
	                	if($NO_UBAH_STATUS!=""){
	                		$sqlupdatecont = "UPDATE t_no_kontainer SET STATUS = '1' WHERE NO_UBAH_STATUS = '".$NO_UBAH_STATUS."' AND NO_CONT= '".$CONT[$c]['_c']['NO_CONT']['_v']."'";
							$Executupdate = $conn->execute($sqlupdatecont); 
	                	}             	
	                }
	            }
	        } elseif ($countCont == 1) {	            
	            	$insertdtl = "INSERT INTO t_request_plp_cont (ID,NO_CONT,KD_CONT_UKURAN) VALUES (".$ID.",'".$CONT['_c']['NO_CONT']['_v']."','".$CONT['_c']['UK_CONT']['_v']."')";
	                $Executedtl = $conn->execute($insertdtl);	            
	                if($Executedtl){
	                	$cont .='<CONT>'.$CONT['_c']['NO_CONT']['_v'].'</CONT>';

	                	if($NO_UBAH_STATUS!=""){
	                		$sqlupdatecont = "UPDATE t_no_kontainer SET STATUS = '1' WHERE NO_UBAH_STATUS = '".$NO_UBAH_STATUS."' AND NO_CONT= '".$CONT['_c']['NO_CONT']['_v']."'";
							$Executupdate = $conn->execute($sqlupdatecont); 
	                	}
	                }
	        }
	        $return = '<?xml version="1.0" encoding="UTF-8"?>';
	        $return .= '<DOCUMENT>';
	        $return .= '<RESPON>Berhasil</RESPON>';
	        $return .= '<DETIL>';
	        $return .= $cont;
	        $return .= '</DETIL>';
	        $return .= '</DOCUMENT>';
		}else{
			$return = '<?xml version="1.0" encoding="UTF-8"?>';
	        $return .= '<DOCUMENT>';
	        $return .= '<RESPON>GAGAL</RESPON>';
	        $return .= '</DOCUMENT>';
		}    

        //$SQLmailbox = "INSERT INTO postbox (SNRF, KD_APRF, KD_ORG_SENDER, KD_ORG_RECEIVER, STR_DATA, KD_STATUS, TGL_STATUS)
                        //VALUES (NULL, 'SENTAJUPLP','" . $KD_ORG_SENDER . "','" . $KD_ORG_RECEIVER . "','" . $xml . "','100',NOW())";
        //$Executemailbox = $conn->execute($SQLmailbox);
        $idpostbox = '';// mysql_insert_id();


    } else {
        $return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>string1 BELUM TERDEFINISI</RESPON>';
        $return .= '</DOCUMENT>';
        $idpostbox = '';
    }
    
    $xmlResponse = $return;
    updateLogServices($IDLogServices, $xmlResponse, '',$idpostbox);

    $conn->disconnect();
    return $return;
}

function sendresponPLPasal($string, $string0, $string1) {
    global $CONF, $conn;
    $conn->connect();
    $userName = $string;
    $password = $string0;
    $kdtps = $string1;

    $IDLogServices = insertLogServices($userName, $kdtps, '', '', 'SendresponPLPasal');
    $checkUser = checkUser($userName, $password, $IDLogServices);
    
    $KD_ORG_SENDER = 2; //beacukai
    $KD_ORG_RECEIVER = $checkUser['kdorganisasi']; //tps asal/pengirim plp

    if (!$checkUser['return']) {
        $conn->disconnect();
        $return = $checkUser['message'];
        return $return;
    }

    $SQLData = "SELECT ID,STR_DATA FROM mailbox WHERE KD_ORG_SENDER = ".$KD_ORG_SENDER." AND KD_ORG_RECEIVER = ".$KD_ORG_RECEIVER." AND KD_APRF = 'GETRESPLPASAL' AND KD_STATUS = '200' limit 1";
    $Query = $conn->query($SQLData);
    if ($Query->size() > 0) {
    	$Query->next();        
    	$return = $Query->get("STR_DATA");
    	$iddata = $Query->get("ID");
    	$SQLupdate = "UPDATE mailbox SET KD_STATUS = '300' WHERE ID = '" . $iddata . "'";
    	$Execute = $conn->execute($SQLupdate);
    }else{
    	$return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>Data Tidak ditemukan</RESPON>';
        $return .= '</DOCUMENT>';
        $iddata = '';
    }
    $xmlResponse = $return;
    updateLogServices($IDLogServices, $xmlResponse, 'GET RESPON PLP ASAL DARI CFS', $iddata);    
    return $return;
 }

function sendresponPLPtujuan($string, $string0, $string1) {
    global $CONF, $conn;
    $conn->connect();
    $userName = $string;
    $password = $string0;
    $kdtps = $string1;

    $IDLogServices = insertLogServices($userName, $kdtps, '', '', 'SendresponPLPtujuan');
    $checkUser = checkUser($userName, $password, $IDLogServices);
    
    $KD_ORG_SENDER = 2; //beacukai
    $KD_ORG_RECEIVER = $checkUser['kdorganisasi']; //tps asal/pengirim plp

    if (!$checkUser['return']) {
        $conn->disconnect();
        $return = $checkUser['message'];
        return $return;
    }

    $SQLData = "SELECT ID,STR_DATA FROM mailbox WHERE KD_ORG_SENDER = ".$KD_ORG_SENDER." AND KD_ORG_RECEIVER = ".$KD_ORG_RECEIVER." AND KD_APRF = 'GETRESPLPTUJUAN' AND KD_STATUS = '200' limit 1";
    $Query = $conn->query($SQLData);
    if ($Query->size() > 0) {
    	$Query->next();        
    	$return = $Query->get("STR_DATA");
    	$iddata = $Query->get("ID");
    	$SQLupdate = "UPDATE mailbox SET KD_STATUS = '300' WHERE ID = '" . $iddata . "'";
    	$Execute = $conn->execute($SQLupdate);
    }else{
    	$return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>Data Tidak ditemukan</RESPON>';
        $return .= '</DOCUMENT>';
        $iddata = '';
    }
    $xmlResponse = $return;
    updateLogServices($IDLogServices, $xmlResponse, 'GET RESPON PLP TUJUAN DARI CFS', $iddata);    
    return $return;
}

function receivebatalPLP($string, $string0, $string1) {
    global $CONF, $conn;
    $conn->connect();
    $userName = $string;
    $password = $string0;
    $xml = str_replace('&', ' ', $string1);

    $IDLogServices = insertLogServices($userName, $xml, '', '', 'ReceivedbatalPLP');

    $checkUser = checkUser($userName, $password, $IDLogServices);
    
    //$KD_ORG_SENDER = $checkUser['kdorganisasi']; //tps asal/pengirim plp
    //$KD_ORG_RECEIVER = 2; //beacukai

    if (!$checkUser['return']) {
        $conn->disconnect();
        $return = $checkUser['message'];
        return $return;
    }


    if ($xml != '') {
        $xml = xml2ary($xml);        
        $HEADER = $xml['DOCUMENT']['_c']['BATALPLP']['_c']['HEADER']['_c'];
        $CONT = $xml['DOCUMENT']['_c']['BATALPLP']['_c']['DETIL']['_c']['CONT'];        
        $countCont = count($CONT);

        $sqlcekrespon = "SELECT A.ID,B.KD_GUDANG_ASAL FROM t_respon_plp_asal_hdr A INNER JOIN t_request_plp_hdr B ON B.REF_NUMBER = A.REF_NUMBER WHERE A.NO_PLP = '".$HEADER['NO_PLP']['_v']."' AND DATE_FORMAT(A.TGL_PLP,'%d%m%Y') = '".$HEADER['TGL_PLP']['_v']."' AND A.KD_TPS= '".$HEADER['KD_TPS']['_v']."' AND A.REF_NUMBER='".$HEADER['REF_NUMBER']['_v']."'";        

        $Querycekrespon = $conn->query($sqlcekrespon);
        if ($Querycekrespon->size() > 0) {
        	$Querycekrespon->next();
			$IDRESPON = $Querycekrespon->get("ID");
			$KD_GD_ASAL = $Querycekrespon->get("KD_GUDANG_ASAL");
        }else{
        	$return = '<?xml version="1.0" encoding="UTF-8"?>';
	        $return .= '<DOCUMENT>';
	        $return .= '<RESPON>GAGAL</RESPON>';
	        $return .= '</DOCUMENT>';
	        $xmlResponse = $return;
    		updateLogServices($IDLogServices, $xmlResponse, 'Anda tidak memiliki hak akses','');
    		return $return;
        }


		$inserthdr = "INSERT INTO t_request_batal_plp_hdr (KD_RESPON_PLP_ASAL,TIPE_DATA,KD_KPBC,NO_SURAT,TGL_SURAT,KD_TPS,KD_GUDANG,NM_PEMOHON,ALASAN,
													 REF_NUMBER,TGL_STATUS) 
					  VALUES (".$IDRESPON.",'".$HEADER['TIPE_DATA']['_v']."','".$HEADER['KD_KANTOR']['_v']."','".$HEADER['NO_SURAT']['_v']."',STR_TO_DATE('".$HEADER['TGL_SURAT']['_v']."','%d%m%Y'),'".$HEADER['KD_TPS']['_v']."','".$KD_GD_ASAL."', '".$HEADER['NM_PEMOHON']['_v']."','".$HEADER['ALASAN']['_v']."', '".$HEADER['REF_NUMBER']['_v']."',NOW())";
		
		$Execute = $conn->execute($inserthdr);
		$ID = mysql_insert_id();
		
		if($ID!=""){			
			$cont = '';
			$sqlcek = "SELECT A.ID,A.NO_UBAH_STATUS FROM t_ubah_status A INNER JOIN t_no_kontainer B ON B.NO_UBAH_STATUS=A.NO_UBAH_STATUS WHERE  A.NO_BC11='".$HEADER['NO_BC11']['_v']."' AND DATE_FORMAT(A.TGL_BC11,'%d%m%Y') = '".$HEADER['TGL_BC11']['_v']."'   ";
        	$Querycek = $conn->query($sqlcek);
        	$NO_UBAH_STATUS = '';
			if ($Querycek->size() > 0) {
				$Querycek->next();
				$IDCEK = $Querycek->get("ID");
				$NO_UBAH_STATUS = $Querycek->get("NO_UBAH_STATUS");
				$sqlupdate = "UPDATE t_ubah_status SET KD_STATUS = '500', TGL_UBAH_STATUS = NOW() WHERE ID = ".$IDCEK;
				$Executupdate = $conn->execute($sqlupdate); 
			}	

			if ($countCont > 1) {
	            for ($c = 0; $c < $countCont; $c++) {	                
	                $insertdtl = "INSERT INTO t_request_batal_plp_cont (ID,NO_CONT,KD_CONT_UKURAN) VALUES (".$ID.",'".$CONT[$c]['_c']['NO_CONT']['_v']."','".$CONT[$c]['_c']['UK_CONT']['_v']."')";
	                $Executedtl = $conn->execute($insertdtl);              
	                if($Executedtl){
	                	$cont .='<CONT>'.$CONT[$c]['_c']['NO_CONT']['_v'].'</CONT>';
	                	if($NO_UBAH_STATUS!=""){
	                		$sqlupdatecont = "UPDATE t_no_kontainer SET STATUS = '2' WHERE NO_UBAH_STATUS = '".$NO_UBAH_STATUS."' AND NO_CONT= '".$CONT[$c]['_c']['NO_CONT']['_v']."'";
							$Executupdate = $conn->execute($sqlupdatecont); 
	                	}             	
	                }
	            }
	        } elseif ($countCont == 1) {	            
	            	$insertdtl = "INSERT INTO t_request_batal_plp_cont (ID,NO_CONT,KD_CONT_UKURAN) VALUES (".$ID.",'".$CONT['_c']['NO_CONT']['_v']."','".$CONT['_c']['UK_CONT']['_v']."')";
	                $Executedtl = $conn->execute($insertdtl);	            
	                if($Executedtl){
	                	$cont .='<CONT>'.$CONT['_c']['NO_CONT']['_v'].'</CONT>';

	                	if($NO_UBAH_STATUS!=""){
	                		$sqlupdatecont = "UPDATE t_no_kontainer SET STATUS = '2' WHERE NO_UBAH_STATUS = '".$NO_UBAH_STATUS."' AND NO_CONT= '".$CONT['_c']['NO_CONT']['_v']."'";
							$Executupdate = $conn->execute($sqlupdatecont); 
	                	}
	                }
	        }

	        $return = '<?xml version="1.0" encoding="UTF-8"?>';
	        $return .= '<DOCUMENT>';
	        $return .= '<RESPON>Berhasil</RESPON>';
	        $return .= '<DETIL>';
	        $return .= $cont;
	        $return .= '</DETIL>';
	        $return .= '</DOCUMENT>';
		}else{
			$return = '<?xml version="1.0" encoding="UTF-8"?>';
	        $return .= '<DOCUMENT>';
	        $return .= '<RESPON>GAGAL</RESPON>';
	        $return .= '</DOCUMENT>';
		}
	    
	    

        //$SQLmailbox = "INSERT INTO postbox (SNRF, KD_APRF, KD_ORG_SENDER, KD_ORG_RECEIVER, STR_DATA, KD_STATUS, TGL_STATUS)
                        //VALUES (NULL, 'SENTAJUPLP','" . $KD_ORG_SENDER . "','" . $KD_ORG_RECEIVER . "','" . $xml . "','100',NOW())";
        //$Executemailbox = $conn->execute($SQLmailbox);
        $idpostbox = '';// mysql_insert_id();


    } else {
        $return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>string1 BELUM TERDEFINISI</RESPON>';
        $return .= '</DOCUMENT>';
        $idpostbox = '';
    }
    
    $xmlResponse = $return;
    updateLogServices($IDLogServices, $xmlResponse, '',$idpostbox);

    $conn->disconnect();
    return $return;
}

function insertLogServices($userName, $xmlRequest, $xmlResponse, $remarks, $method = '') {

    global $CONF, $conn;
    $ipAddress = getIP();
    $method = $method == '' ? 'NULL' : "'" . $method . "'";
    $userName = $userName == '' ? 'NULL' : "'" . $userName . "'";
    $xmlRequest = $xmlRequest == '' ? 'NULL' : "'" . $xmlRequest . "'";
    $xmlResponse = $xmlResponse == '' ? 'NULL' : "'" . $xmlResponse . "'";
    $remarks = $remarks == '' ? 'NULL' : "'" . $remarks . "'";
    $SQL = "INSERT INTO app_log_server (METHOD, USERNAME, XML_REQUEST, XML_RESPONSE, IPADDRESS, REMARKS, WK_REKAM)
            VALUES (" . $method . ", " . $userName . ", " . $xmlRequest . ", " . $xmlResponse . ", '" . $ipAddress . "', " . $remarks . ", NOW())";
     
    $Execute = $conn->execute($SQL);
    $ID = mysql_insert_id();
    return $ID;
}

function getIP($type = 0) {
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else {
        $ip = "unknown";
        return $ip;
    }
    if ($type == 1) {
        return md5($ip);
    }
    if ($type == 0) {
        return $ip;
    }
}

function updateLogServices($ID, $xmlResponse, $remarks,$iddata) {
    global $CONF, $conn;
    $xmlResponse = $xmlResponse == '' ? 'NULL' : "'" . $xmlResponse . "'";
    $remarks = $remarks == '' ? 'NULL' : "'" . $remarks . "'";
    $iddata = $iddata == '' ? 'NULL' : "'" . $iddata . "'";
    $SQL = "UPDATE app_log_server SET XML_RESPONSE = " . $xmlResponse . ", REMARKS = " . $remarks . ", IDDATA = " . $iddata . "
            WHERE ID = '" . $ID . "'";
    $Execute = $conn->execute($SQL);
}

function checkUser($user, $password, $IDLogServices) {
    global $CONF, $conn;
    $SQL = "SELECT B.KD_TIPE_ORGANISASI,B.ID
            FROM app_user_ws A INNER JOIN t_organisasi B ON A.KD_ORGANISASI = B.ID
            WHERE A.USERLOGIN = '" . trim($user) . "'
                  AND A.PASSWORD = '" . trim($password) . "'";
    $Query = $conn->query($SQL);
    if ($Query->size() == 0) {
        $return['return'] = false;
        $return['message'] = '<?xml version="1.0" encoding="UTF-8"?>';
        $return['message'] .= '<DOCUMENT>';
        $return['message'] .= '<RESPON>USERNAME ATAU PASSWORD SALAH.</RESPON>';
        $return['message'] .= '</DOCUMENT>';
        $logServices = updateLogServices($IDLogServices, $return['message'], 'USERNAME ATAU PASSWORD SALAH.');
    } else {
    	$Query->next();        
        $return['return'] = true;
        $return['kdorganisasi'] = $Query->get("ID");
    }
    return $return;
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>