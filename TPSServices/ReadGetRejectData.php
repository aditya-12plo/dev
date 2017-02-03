<?php

set_time_limit(3600);
require_once("config.php");

$method = 'READGETREJECTDATA';
$KdAPRF = 'GETREJECTDATA';
$filename = $CONF['root.dir'] . "CheckScheduler/" . $method . ".txt";
$main = new main($CONF, $conn);
$CheckFile = $main->CheckFile($filename);
if (!$CheckFile) {
    $createFile = $main->createFile($filename);
    $main->connect();

    //BEGIN
    $SQL = "SELECT ID, STR_DATA
            FROM mailbox A 
            WHERE A.KD_STATUS = '100'
                  AND KD_APRF = '" . $KdAPRF . "'
            LIMIT 0,10";
    $Query = $conn->query($SQL);
    if ($Query->size() > 0) {
        while ($Query->next()) {
            $ID_MAILBOX = $Query->get("ID");
            $STR_DATA = $Query->get("STR_DATA");

            $xml = xml2ary($STR_DATA);
            if (count($xml) > 0) {
                $xml = $xml['DOCUMENT']['_c'];
                $countReject = count($xml['REJECT']);
                if ($countReject > 1) {
                    for ($c = 0; $c < $countReject; $c++) {
                        $DOKREJECT = $xml['REJECT'][$c]['_c'];
                        InsertDokumenReject($DOKREJECT);
                    }
                } elseif ($countReject == 1) {
                    $DOKREJECT = $xml['REJECT']['_c'];
                    InsertDokumenReject($DOKREJECT);
                }
            }
            $SQL = "UPDATE mailbox SET KD_STATUS = '200', TGL_STATUS = NOW()
                    WHERE ID = '" . $ID_MAILBOX . "'";
            $Execute = $conn->execute($SQL);
            echo $SQL . '<br>';
        }
    } else {
        echo 'data tidak ada.';
    }
    //END

    $main->connect(false);
    $main->removeFile($filename);
} else {
    echo 'Scheduler sedang berjalan, harap menghapus file ' . $method . '.txt yang ada difolder CheckScheduler.';
}

function InsertDokumenReject($DOKREJECT) {
    global $CONF, $conn;
    $header = $DOKREJECT['HEADER']['_c'];

    $REF_NUMBER = trim($header['REF_NUMBER']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['REF_NUMBER']['_v'])) . "'";
    $NO_CONT = trim($header['NO_CONT']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['NO_CONT']['_v'])) . "'";
    $KD_REJECT = trim($header['KD_REJECT']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['KD_REJECT']['_v'])) . "'";
    $TGL_REJECT = trim($header['TGL_REJECT']['_v']) == "" ? "NULL" : "STR_TO_DATE('" . strtoupper(trim($header['TGL_REJECT']['_v'])) . "','%m/%d/%Y')";

    $SQL = "INSERT INTO t_doc_reject (REF_NUMBER, NO_CONT, KD_REJECT, TGL_REJECT, WK_REKAM)
            VALUES (" . $REF_NUMBER . ", " . $NO_CONT . ", " . $KD_REJECT . ", " . $TGL_REJECT . ", NOW())";
    $Execute = $conn->execute($SQL);
}

?>