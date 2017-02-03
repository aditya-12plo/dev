<?php

set_time_limit(3600);
require_once("config.php");
$CONF['url.wsdl'] = 'http://103.29.187.109/TPSServices/services.php';
$method = 'CheckConnection';
$KdAPRF = '';
$SufixMethod = '';
$filename = $CONF['root.dir'] . "CheckScheduler/" . $method . "" . $SufixMethod . ".txt";
$main = new main($CONF, $conn);
$CheckFile = $main->CheckFile($filename);
if (!$CheckFile) {
    $createFile = $main->createFile($filename);
    $main->connect();

    $SOAPAction = 'http://services.beacukai.go.id/CheckConnection';
    $SQL = "SELECT B.USERNAME_TPSONLINE_BC, B.PASSWORD_TPSONLINE_BC
            FROM t_organisasi B 
            WHERE B.KD_TIPE_ORGANISASI IN ('TPS','TPS1','TPS2')";
    $Query = $conn->query($SQL);
    if ($Query->size() > 0) {
        while ($Query->next()) {
            $USERNAME_TPSONLINE_BC = $Query->get("USERNAME_TPSONLINE_BC");
            $PASSWORD_TPSONLINE_BC = $Query->get("PASSWORD_TPSONLINE_BC");

            $xml = '<soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.beacukai.go.id/">
                    <soapenv:Header/>
                        <soapenv:Body>
                           <ser:CheckConnection soapenv:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/">
                              <Username xsi:type="xsd:string">' . $USERNAME_TPSONLINE_BC . '</Username>
                              <Password xsi:type="xsd:string">' . $PASSWORD_TPSONLINE_BC . '</Password>
                           </ser:CheckConnection>
                        </soapenv:Body>
                     </soapenv:Envelope>';
            $Send = $main->SendCurl($xml, $CONF['url.wsdl'], $SOAPAction, $CONF['proxyhost'] . ":" . $CONF['proxyport'],'80');
            echo '<pre>';
            print_r($Send);
            echo '</pre>';
            if ($Send['response'] != '') {
                $arr1 = 'CheckConnectionResponse';
                $arr2 = 'CheckConnectionResult';
                $response = xml2ary($Send['response']);
                $response = $response['soap:Envelope']['_c']['soap:Body']['_c'][$arr1]['_c'][$arr2]['_v'];
            } else {
                $response = '';
            }
        }
    }
    echo $response;

    $main->connect(false);
    $main->removeFile($filename);
} else {
    echo 'Scheduler sedang berjalan, harap menghapus file ' . $method . '' . $SufixMethod . '.txt yang ada difolder CheckScheduler.';
}
?>