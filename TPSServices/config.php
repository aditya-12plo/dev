<?php

date_default_timezone_set('Asia/Jakarta');
//error_reporting(-1);
ini_set('display_error', 'E_ALL');
$CONF['root.dir'] = $_SERVER['DOCUMENT_ROOT'].'/dev/TPSServices/';
#$CONF['root.dir'] = '/var/www/html/dev/cfs-center/TPSServices/';
//$CONF['root.dir'] = 'F:/WEB/TPS-ONLINE-MASS/TPSServices/';
$CONF['url.wsdl'] = 'https://tpsonline.beacukai.go.id/tps/service.asmx';
//$CONF['url.wsdl'] = 'https://202.137.230.158/tps/service.asmx';
#$CONF['proxyhost'] = "192.168.4.3";
#$CONF['proxyport'] = "8080";

$CONF['host'] = "localhost";
$CONF['username'] = "root";
#$CONF['password'] = "tps0nl1n3@3d11";
$CONF['password'] = "";
$CONF['database'] = "tps_npct1";
$CONF['port'] = "3306";

/* $CONF['host'] = "localhost";
  $CONF['username'] = "root";
  $CONF['password'] = "phpMyAdmin";
  $CONF['database'] = "dbport";
  $CONF['port'] = "3306"; */

require_once($CONF['root.dir'] . "Libraries/functions.php");
require_once($CONF['root.dir'] . "Libraries/xml2array.php");
require_once($CONF['root.dir'] . "ClassData/main.class.php");

//setting database
require_once($CONF['root.dir'] . "Libraries/dbLite/DBManager.php");
$conn = new DBManager($DB_MANAGER->DB_MYSQL);
$conn->parseURL("db.MYSQL://" . $CONF['username'] . ":" . $CONF['password'] . "@" . $CONF['host']);
$conn->setDBName($CONF['database']);
?>