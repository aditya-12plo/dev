<?php

set_time_limit(3600);
require_once("config.php");

$method = 'READGETRESPONBTLPLPASAL';
$KdAPRF = 'GETRESBTLPLPASAL';
$filename = $CONF['root.dir'] . "CheckScheduler/" . $method . ".txt";
$main = new main($CONF, $conn);
$CheckFile = $main->CheckFile($filename);
if (!$CheckFile) {
    #$createFile = $main->createFile($filename);
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
                $countPLP = count($xml['RESPON_BATAL']);
                if ($countPLP > 1) {
                    for ($c = 0; $c < $countPLP; $c++) {
                        $RESPONPLP = $xml['RESPON_BATAL'][$c]['_c'];
                        InsertBatalPLPResponAsal($RESPONPLP);
                    }
                } elseif ($countPLP == 1) {
                    $RESPONPLP = $xml['RESPON_BATAL']['_c'];
                    InsertBatalPLPResponAsal($RESPONPLP);
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
	
	//UPDATE AFTER GET RESPON
	$SQL = "SELECT B.ID FROM t_respon_batal_plp_asal_hdr A
			INNER JOIN t_request_batal_plp_hdr B ON B.REF_NUMBER=A.REF_NUMBER
			WHERE B.KD_STATUS = '400'";
	$Execute = $conn->query($SQL);
	if ($Execute->size() > 0) {
		while ($Execute->next()){
			$REQ_ID = $Execute->get("ID");
			$SQL = "UPDATE t_request_batal_plp_hdr SET KD_STATUS = '600', TGL_STATUS = NOW() WHERE ID = '".$REQ_ID."'";
			$Query = $conn->execute($SQL);
		}
	}
    $main->connect(false);
    $main->removeFile($filename);
} else {
    echo 'Scheduler sedang berjalan, harap menghapus file ' . $method . '.txt yang ada difolder CheckScheduler.';
}

function InsertBatalPLPResponAsal($RESPONPLP) {
	global $CONF, $conn;
    $header = $RESPONPLP['HEADER']['_c'];
    $KD_KANTOR = trim($header['KD_KANTOR']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['KD_KANTOR']['_v'])) . "'";
    $KD_TPS = trim($header['KD_TPS']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['KD_TPS']['_v'])) . "'";
	$REF_NUMBER = trim($header['REF_NUMBER']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['REF_NUMBER']['_v'])) . "'";
	$NO_PLP = trim($header['NO_BATAL_PLP']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($header['NO_BATAL_PLP']['_v'])) . "'";
    $TGL_PLP = trim($header['TGL_BATAL_PLP']['_v']) == "" ? "NULL" : "STR_TO_DATE('" . strtoupper(trim($header['TGL_BATAL_PLP']['_v'])) . "','%Y%m%d')";
    #echo $NO_PLP . '-' . $TGL_PLP . '<br>';
    $SQL = "SELECT ID
            FROM t_respon_batal_plp_asal_hdr
            WHERE NO_BATAL_PLP = ".$NO_PLP." 
                  AND TGL_BATAL_PLP = ".$TGL_PLP."";
    $Query = $conn->query($SQL);
    if ($Query->size() == 0){
        $SQL = "INSERT INTO t_respon_batal_plp_asal_hdr(KD_KPBC, KD_TPS, NO_BATAL_PLP, TGL_BATAL_PLP, REF_NUMBER, KD_STATUS, TGL_STATUS)
                VALUES (".$KD_KANTOR.", ".$KD_TPS.", ".$NO_PLP.", ".$TGL_PLP.", ".$REF_NUMBER.", '100', NOW())";
        $Execute = $conn->execute($SQL);
		echo $SQL . '<br>';
        $ID = mysql_insert_id();
        if ($ID != '') {
            $detil = $RESPONPLP['DETIL']['_c'];
            $countCONT = count($detil['CONT']);
            if ($countCONT > 1) {
                for ($d = 0; $d < $countCONT; $d++) {
                    $CONT = $detil['CONT'][$d]['_c'];
                    InsertKontainer($ID, $CONT);
                }
            } elseif ($countCONT == 1) {
                $CONT = $detil['CONT']['_c'];
                InsertKontainer($ID, $CONT);
            }
        }
    }
}
function InsertKontainer($ID, $CONT) {
    global $CONF, $conn;
    $NO_CONT = trim($CONT['NO_CONT']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($CONT['NO_CONT']['_v'])) . "'";
    $UK_CONT = trim($CONT['UK_CONT']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($CONT['UK_CONT']['_v'])) . "'";
    //add tag element
    $FL_SETUJU = trim($CONT['FL_SETUJU']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($CONT['FL_SETUJU']['_v'])) . "'";
    $JNS_CONT = trim($CONT['JNS_CONT']['_v']) == "" ? "NULL" : "'" . strtoupper(trim($CONT['JNS_CONT']['_v'])) . "'";
    $SQL = "INSERT INTO t_respon_batal_plp_asal_cont (ID, NO_CONT, KD_CONT_UKURAN, KD_CONT_JENIS, KD_STATUS)
            VALUES (" . $ID . ", " . $NO_CONT . ", " . $UK_CONT . ", " . $JNS_CONT . ", " .$FL_SETUJU.")";
    $Execute = $conn->execute($SQL);
    echo $SQL . '<br>';
}

?>