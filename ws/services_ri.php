<?php
//ini_set("display_errors", "0");
error_reporting(E_ERROR); # report all errors
ini_set("display_errors", "1");
$version = substr(phpversion(),0,1);
if ($version=='5') date_default_timezone_set('asia/jakarta');//For PHP 5
require_once('nusoap_new/lib/nusoap.php');
include('xml2array.php');
$server = new soap_server();
$server->configureWSDL('RIServices', 'urn:RIServices');

$server->register('getRI',
	array('user'   => 'xsd:string', 
		  'pass'   =>'xsd:string',
		  'npwp'   =>'xsd:string'),
	array('return' => 'xsd:string'),
	'urn:getRI',
	'urn:getRI#getRI',
	'rpc',
	'encoded',
	'Get Data Rekomendasi Impor'
);

function conn()
{
	$conn = mysql_connect('localhost','root','');
	mysql_select_db('liu',$conn);
}

function cek_user($user, $pass)
{
	$array_user = array();
	$sql = "SELECT COUNT(*) AS banyak 
			FROM tbl_akses 
			WHERE username='".trim(addslashes($user))."' 
			AND password='".trim(addslashes($pass))."' 
			AND valid='1' AND akses='kemenhut'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	return $row['banyak'];
}

function get_format_date($tanggal)
{
	$returnDate = "";
	
	if (($tanggal!="") and ($tanggal!="0000-00-00"))
		$returnDate = str_replace("-","",$tanggal);
	
	return $returnDate;
}

function WhiteSpaceXML($text) {
	$hasil = str_replace("&","&amp;",$text);
	$hasil = str_replace("'","&apos;",$hasil);
	$hasil = str_replace("\"","&quot;",$hasil);
	$hasil = str_replace("<","&lt;",$hasil);
	$hasil = str_replace(">","&gt;",$hasil);
	$hasil = str_replace("°","&deg;",$hasil);
	$hasil = str_replace("\n"," ", $hasil);
		
	return $hasil;
}

function getRI($user,$pass,$npwp)
{
	conn();
	$stringReturn = "R99";
	$textReturn = "Data Kosong";
	$banyak_cek = cek_user($user, $pass);
	$add_xml = "";
	if($banyak_cek > 0)
	{
		if ($npwp != "")
		{
			$add_xml = getDataRI($npwp);
			$stringReturn = ($add_xml != "") ? "R00" : "R99";
			$textReturn = ($add_xml != "") ? "SUCCESS" : "GAGAL";
		}
	} 
	else 
	{
		$textReturn = "Data Tidak Dikenali";
	}
	$xmlReturn = '<?xml version="1.0" encoding="UTF-8"?>
				  <document_ri>
				  		<return_cek>'.$stringReturn.'</return_cek>
						<keterangan>'.$textReturn.'</keterangan>
						'.$add_xml.'
				  </document_ri>';
	mysql_close();	
	return $xmlReturn;
}

function getDataRI($npwp)
{
	$sql = "SELECT A.ID, A.NPWP_PERUSAHAAN, CASE WHEN A.JENIS_API='1' THEN 'API-P' ELSE 'API-U' END AS JENIS,
			CONCAT(B.BENTUK_USAHA,'. ',A.NAMA_PERUSAHAAN) AS NAMA, A.ALAMAT_PERUSAHAAN, A.TELP_PERUSAHAAN, A.FAX_PERUSAHAAN, A.NO_API,
			A.TGL_TTD, A.TGL_REG_ULANG
			FROM TBL_PERMOHONAN A
			LEFT JOIN MST_BENTUK_USAHA B ON B.ID_BENTUK_USAHA=A.ID_BENTUK_USAHA
			WHERE A.STATUS_PERMOHONAN = '1' 
			AND DATE(A.TGL_REG_ULANG) >= DATE(NOW())
			AND A.NPWP_PERUSAHAAN = '".$npwp."'";
	#echo $sql; die();
	$result = mysql_query($sql);
	$banyak = mysql_num_rows($result);
	$xmlReturn = "";
	if ($banyak > 0)
	{	
		$xmlReturn .= '<document_api>';
		while ($row = mysql_fetch_array($result,MYSQL_ASSOC))
		{
			$xmlReturn .= '<npwp>'.$row['NPWP_PERUSAHAAN'].'</npwp>';
			$xmlReturn .= '<nama>'.WhiteSpaceXML($row['NAMA']).'</nama>';
			$xmlReturn .= '<alamat>'.WhiteSpaceXML($row['ALAMAT_PERUSAHAAN']).'</alamat>';
			$xmlReturn .= '<telp>'.$row['TELP_PERUSAHAAN'].'</telp>';
			$xmlReturn .= '<fax>'.$row['FAX_PERUSAHAAN'].'</fax>';
			$xmlReturn .= '<jenis_api>'.$row['JENIS'].'</jenis_api>';
			$xmlReturn .= '<no_api>'.$row['NO_API'].'</no_api>';
			$xmlReturn .= '<tgl_api>'.$row['TGL_TTD'].'</tgl_api>';
			$xmlReturn .= '<tgl_akhir>'.$row['TGL_REG_ULANG'].'</tgl_akhir>';
			
			$sqlSection = "SELECT A.ID_PERMOHONAN, A.ID_BAGIAN, B.NOMOR_BAGIAN, B.BATAS_AWAL, B.BATAS_AKHIR
						   FROM TBL_BAGIAN A
						   LEFT JOIN MST_KLASIFIKASI_HS B ON B.ID=A.ID_BAGIAN
						   WHERE A.ID_PERMOHONAN = '".$row['ID']."'";
			$resultSection = mysql_query($sqlSection);
			$banyakSection = mysql_num_rows($resultSection);
			if($banyakSection > 0)
			{
				$xmlReturn .= '<loop>';
				while ($arraySection = mysql_fetch_array($resultSection,MYSQL_ASSOC))
				{
					$xmlReturn .= '<id_bagian>'.$arraySection['ID_BAGIAN'].'</id_bagian>';
					$xmlReturn .= '<no_bagian>'.$arraySection['NOMOR_BAGIAN'].'</no_bagian>';
					$xmlReturn .= '<hs_awal>'.$arraySection['BATAS_AWAL'].'</hs_awal>';
					$xmlReturn .= '<hs_akhir>'.$arraySection['BATAS_AKHIR'].'</hs_akhir>';
				}
				$xmlReturn .= '</loop>';
			}
		}
		$xmlReturn .= '</document_api>';
	}
	return $xmlReturn;
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);

?>