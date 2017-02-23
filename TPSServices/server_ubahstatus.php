<?php

ob_start();
// call library
/*
require_once ('config.php' );
require_once ($CONF['root.dir'] . 'Libraries/nusoap/nusoap.php' );
require_once ($CONF['root.dir'] . 'Libraries/xml2array.php' );
*/

require_once ('Libraries/nusoap/nusoap.php' );
require_once ('Libraries/xml2array.php' );

// create instance
$server = new soap_server();

// initialize WSDL support
$server->configureWSDL('CFSCenter', 'urn:CFSCenterwsdl');

// place schema at namespace with prefix tns
$server->wsdl->schemaTargetNamespace = 'urn:CFSCenterwsdl';

// register method	
$server->register('SendUbahStatus', // method name
      array('string' => 'xsd:string', 'string0' => 'xsd:string'),
              // input parameter
        array('return' => 'xsd:string'), // output
        'urn:SendUbahStatuswsdl', // namespace
        'urn:CFSCenter', // soapaction
        'rpc', // style
        'encoded', // use
        'SendUbahStatus'// documentation
);


$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);


function GetUbahStatus($string, $string0) {
    
global $CONF, $conn;
$conn->connect();
$username = $string;
$password = $string0;
$xml = '';

	$IDLogServices = insertLogServices($userName, $xml, '', '', 'SendUbahStatus');

    $checkUser = checkUser($userName, $password, $IDLogServices);
	
    if (!$checkUser['return']) {
        $conn->disconnect();
        $return = $checkUser['message'];
        return $return;
    }
	
	  if ($xml != '') {
        $xml = xml2ary($xml);
        $DOCUMENT = $xml['DOCUMENT']['_c']['UBAHSTTS'];
        $countDocument = count($DOCUMENT);
        if ($countDocument > 0) {
            for ($c = 0; $c < $countDocument; $c++) {
                $DocumentUBAHSTTS = InsertDocumentUBAHSTTS($DOCUMENT[$c]['_c'], $IDLogServices);
                if (!$DocumentUBAHSTTS['return']) {
                    $return = $DocumentUBAHSTTS['message'];
                    $conn->disconnect();
                    return $return;
                }
            }
        } 
		else {
        $return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>DATA BELUM TERSEDIA</RESPON>';
        $return .= '</DOCUMENT>';
        }
        $return = $DocumentUBAHSTTS['return'] ? 'true' : 'false';
    } else {
        $return = '<?xml version="1.0" encoding="UTF-8"?>';
        $return .= '<DOCUMENT>';
        $return .= '<RESPON>string BELUM TERDEFINISI</RESPON>';
        $return .= '</DOCUMENT>';
    }
    $xmlResponse = $DocumentUBAHSTTS['message'] != '' ? $DocumentUBAHSTTS['message'] : $return;
    updateLogServices($IDLogServices, $xmlResponse, $remarks);

    $conn->disconnect();
    return $return;

}


function checkUser($user, $password, $IDLogServices) {
    global $CONF, $conn;
    $SQL = "SELECT B.KD_TPS
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
    	//$Query->next();
        //$SERI = $Query->get("SERI");
        $return['return'] = true;

    }
    return $return;
}





?>

