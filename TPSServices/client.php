<?php

require_once ('config.php' );
require_once ($CONF['root.dir'] . 'Libraries/nusoap/nusoap.php' );
require_once ($CONF['root.dir'] . 'Libraries/xml2array.php' );

$xml = '<?xml version="1.0" encoding="utf-8"?>
            <DOCUMENT xmlns="cocokms.xsd">
            <COCOKMS>
            <HEADER>
            <KD_DOK>1</KD_DOK>
            <KD_TPS>GIMP</KD_TPS>
            <NM_ANGKUT>TEST DATA</NM_ANGKUT>
            <NO_VOY_FLIGHT>MH727</NO_VOY_FLIGHT>
            <CALL_SIGN>-</CALL_SIGN>
            <TGL_TIBA>20160222</TGL_TIBA>
            <KD_GUDANG>GPRA</KD_GUDANG>
            <REF_NUMBER>GRA1160223000012</REF_NUMBER>
            </HEADER>
            <DETIL>
            <KMS>
            <NO_BL_AWB>111</NO_BL_AWB>
            <TGL_BL_AWB>20160223</TGL_BL_AWB>
            <NO_MASTER_BL_AWB>111</NO_MASTER_BL_AWB>
            <TGL_MASTER_BL_AWB>20160223</TGL_MASTER_BL_AWB>
            <ID_CONSIGNEE>000000000000003</ID_CONSIGNEE>
            <CONSIGNEE>PT. SAMSUNG ELECTRONIC INDONESIA</CONSIGNEE>
            <BRUTO>111</BRUTO>
            <NO_BC11>006573</NO_BC11>
            <TGL_BC11>20160222</TGL_BC11>
            <NO_POS_BC11>111</NO_POS_BC11>
            <CONT_ASAL>
            </CONT_ASAL>
            <SERI_KEMAS>3</SERI_KEMAS>
            <KD_KEMAS>PK</KD_KEMAS>
            <JML_KEMAS>111</JML_KEMAS>
            <KD_TIMBUN>111</KD_TIMBUN>
            <KD_DOK_INOUT>
            </KD_DOK_INOUT>
            <NO_DOK_INOUT>
            </NO_DOK_INOUT>
            <TGL_DOK_INOUT>00000000</TGL_DOK_INOUT>
            <WK_INOUT>20160223092823</WK_INOUT>
            <KD_SAR_ANGKUT_INOUT>
            </KD_SAR_ANGKUT_INOUT>
            <NO_POL>
            </NO_POL>
            <PEL_MUAT>MYKUL</PEL_MUAT>
            <PEL_TRANSIT>
            </PEL_TRANSIT>
            <PEL_BONGKAR>IDCGK</PEL_BONGKAR>
            <GUDANG_TUJUAN>
            </GUDANG_TUJUAN>
            <KODE_KANTOR>050100</KODE_KANTOR>
            <NO_DAFTAR_PABEAN>
            </NO_DAFTAR_PABEAN>
            <TGL_DAFTAR_PABEAN>
            </TGL_DAFTAR_PABEAN>
            <NO_SEGEL_BC>
            </NO_SEGEL_BC>
            <TGL_SEGEL_BC>
            </TGL_SEGEL_BC>
            <NO_IJIN_TPS>
            </NO_IJIN_TPS>
            <TGL_IJIN_TPS>
            </TGL_IJIN_TPS>
            </KMS>
            </DETIL>
            </COCOKMS>
            </DOCUMENT>';
?>