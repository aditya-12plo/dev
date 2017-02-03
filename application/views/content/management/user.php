<div class="card">
    <div class="card-block p-a-0">
        <div class="box-tab m-b-0" id="rootwizard">
            <ul class="wizard-tabs">
                <li class="active"><a href="#tab1" data-toggle="tab">DAFTAR USER</a></li>
                <li style="width:100%;"> <a data-toggle="" style="text-align:right">
                        <button type="button" class="btn btn-primary btn-icon" onclick="save_popup('form_data','divtbluser'); return false;">Save <i class="icon-check"></i></button>
                    </a> </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane p-x-lg active" id="tab1">
                    <form name="form_data" id="form_data" class="form-horizontal" role="form" action="<?php echo site_url('management/execute/' . $act . '/user/' . $ID); ?>" method="post" autocomplete="off" onsubmit="save_popup('form_data','divtbluser'); return false;">
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">ORGANISASI</label>
                            <div class="col-sm-9">
                                <input type="hidden" class="form-control" name="DATA[KD_ORGANISASI]" id="KD_ORGANISASI" wajib="yes" placeholder="NPWP ORGANISASI" readonly="readonly" value="<?php echo $arrhdr['KD_ORGANISASI']; ?>">
                                <input type="text" name="NAMA_ORGANISASI" id="NAMA_ORGANISASI" wajib="yes" class="form-control" placeholder="NAMA ORGANISASI" value="<?php echo strtoupper($arrhdr['NAMA_ORGANISASI']); ?>" readonly>
                            </div>
                            <div class="col-sm-1" style="padding-top:2px">
                                <button type="button" class="btn btn-primary btn-sm" onclick="popup_searchtwo('popup/popup_search/organisasi/KD_ORGANISASI|NAMA_ORGANISASI/2','','60','600')"> <span class="icon-magnifier"></span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">USERLOGIN</label>
                            <div class="col-sm-10">
                                <input type="text" name="DATA[USERLOGIN]" id="USERLOGIN" wajib="yes" class="form-control" placeholder="USERLOGIN" value="<?php echo $arrhdr['USERLOGIN']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">PASSWORD</label>
                            <div class="col-sm-10">
                                <input type="text" name="DATA[PASSWORD]" id="PASSWORD" <?php echo ($act == "save") ? 'wajib="yes"' : ""; ?> class="form-control" placeholder="PASSWORD">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">NAMA LENGKAP</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="DATA[NM_LENGKAP]" id="NM_LENGKAP" wajib="yes" placeholder="NAMA LENGKAP"  value="<?php echo $arrhdr['NM_LENGKAP']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">HANDPHONE</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="DATA[HANDPHONE]" id="HANDPHONE" wajib="yes" placeholder="HANDPHONE"  value="<?php echo $arrhdr['HANDPHONE']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">EMAIL</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="DATA[EMAIL]" id="EMAIL" wajib="yes" placeholder="EMAIL"  value="<?php echo $arrhdr['EMAIL']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">GUDANG</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="DATA[KD_GUDANG]" id="KD_GUDANG" placeholder="KODE" readonly="readonly" value="<?php echo $arrhdr['KD_GUDANG']; ?>">
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="NAMA_GUDANG" id="NAMA_GUDANG" class="form-control" placeholder="NAMA" value="<?php echo strtoupper($arrhdr['NAMA_GUDANG']); ?>">
                                <input type="hidden" name="DATA[KD_TPS]" id="KD_TPS" class="form-control" placeholder="KODE TPS" value="<?php echo strtoupper($arrhdr['KD_TPS']); ?>">
                            </div>
                            <div class="col-sm-1" style="padding-top:2px">
                                <button type="button" class="btn btn-primary btn-sm" onclick="popup_searchtwo('popup/popup_search/gudang/KD_GUDANG|NAMA_GUDANG|KD_TPS/2','','60','600')"> <span class="icon-magnifier"></span></button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">GROUP</label>
                            <div class="col-sm-10"> <?php echo form_dropdown('DATA[KD_GROUP]', $arr_group, $arrhdr['KD_GROUP'], 'id="KD_GROUP" wajib="yes" class="form-control"'); ?> </div>
                            <input type="hidden" class="form-control" name="ID" id="ID"  placeholder="KODE" value="<?php echo $arrhdr['ID']; ?>">
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label-left">STATUS</label>
                            <div class="col-sm-10">
                                <?php echo form_dropdown('DATA[KD_STATUS]', array('ACTIVE' => 'ACTIVE', 'INACTIVE' => 'INACTIVE'), $arrhdr['KD_STATUS'], 'id="KD_STATUS" wajib="yes" class="form-control"'); ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        autocomplete('NAMA_ORGANISASI','/tps/autocomplete/mst_org',function(event, ui){
            $('#KD_ORGANISASI').val(ui.item.KD_ORGANISASI);
        });
        autocomplete('NAMA_GUDANG','/tps/autocomplete/mst_gudang',function(event, ui){
            $('#KD_TPS').val(ui.item.KD_TPS);
            $('#KD_GUDANG').val(ui.item.KD_GUDANG);
        });
    });
</script> 