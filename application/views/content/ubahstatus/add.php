<div class="card">
  <div class="card-block p-a-0">
    <div class="box-tab m-b-0" id="rootwizard">
      <ul class="wizard-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">UBAH STATUS</a></li>
        <li style="width:100%;"> <a data-toggle="" style="text-align:right">
          <button type="button" class="btn btn-primary btn-icon" onclick="save_post('form_data'); return false;">Save <i class="icon-check"></i></button>
          </a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane p-x-lg active" id="tab1">
          <form name="form_data" id="form_data" class="form-horizontal" role="form" action="<?php echo site_url('status/execute/'.$act.'/'.$ID); ?>" method="post" autocomplete="off" onsubmit="save_post('form_data'); return false;">
            <div class="form-group">
              <label class="col-sm-3 control-label-left">Gudang Asal *</label>
              <div class="col-sm-9"> 
                <input type="text" value="<?=$arrhdr['GUDANGASAL'];?>"  wajib="yes" id="GUDANG_ASAL" class="form-control" placeholder="GUDANG ASAL"> 
                <input type="hidden" name="GUDANG_ASAL2" value="<?=$arrhdr['KD_GUDANG_ASAL'];?>" wajib="yes" id="GUDANG_ASAL2" class="form-control" placeholder="GUDANG ASAL2">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">Gudang Tujuan *</label>
              <div class="col-sm-9"> 
                <input type="text" value="<?=$arrhdr['GUDANGTUJUAN'];?>" value="" wajib="yes" id="GUDANG_TUJUAN" class="form-control" placeholder="GUDANG TUJUAN">
                <input type="hidden" name="GUDANG_TUJUAN2" value="<?=$arrhdr['KD_GUDANG_TUJUAN'];?>" wajib="yes" id="GUDANG_TUJUAN2" class="form-control" placeholder="GUDANG TUJUAN">
              </div>
            </div>
            <br><br><br>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">Nama Pemohon *</label>              
              <div class="col-sm-9"> 
                <input type="text"  name="NM_LENGKAP" id="NM_LENGKAP" class="form-control" placeholder="NAMA PEMOHON" value="<?php echo strtoupper($this->newsession->userdata('NM_LENGKAP')); ?>" readonly> 
              </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label-left">Nama Kapal *</label>
                <div class="col-sm-4"> 
                  <input type="text" wajib="yes" value="<?php echo $arrhdr['NAMA_KAPAL']; ?>" name="NAMA_KAPAL" id="NAMA_KAPAL" class="form-control" placeholder="NAMA KAPAL">                  
                </div>
                <div class="col-sm-5"> 
                  <input type="text" name="NO_VOYAGE" value="<?=$arrhdr['NO_VOY_FLIGHT'];?>" wajib="yes" id="NO_VOYAGE" class="form-control" placeholder="NO VOYAGE">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label-left">Call Sign *</label>
                <div class="col-sm-4">
                  <input type="text" wajib="yes" name="CALL_SIGN" value="<?php echo $arrhdr['CALL_SIGN']; ?>" id="CALL_SIGN" class="form-control" placeholder="CALL SIGN" readonly="readonly">
                </div>
                <div class="col-sm-5"> 
                  <div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="icon-calendar"></i></span>
                    <input class="form-control drp" type="text" value="<?=date_input($arrhdr['TGL_TIBA']);?>"  placeholder="TANGGAL TIBA" name="TGL_TIBA" id="TGL_TIBA" data-provide="datepicker" wajib="yes">
                  </div> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label-left">No. BC 11 *</label>
                <div class="col-sm-4"> 
                  <input type="text" name="NO_BC11" wajib="yes" value="<?php echo $arrhdr['NO_BC11']; ?>" id="NO_BC11" class="form-control" placeholder="No. BC 1.1">
                </div>
                <div class="col-sm-5"> 
                  <div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="icon-calendar"></i></span>
                    <input class="form-control drp" type="text" value="<?=date_input($arrhdr['TGL_BC11']);?>" placeholder="TANGGAL BC11" name="TGL_BC11" id="TGL_BC11" data-provide="datepicker" wajib="yes">
                  </div> 
                </div>
            </div>
            <input type="hidden" name="ID_DATA" value="<?php echo $ID_DATA; ?>" readonly="readonly"/>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(function(){
  date('drp');
  autocomplete('GUDANG_ASAL','/autocomplete/status/reff_gudang/nama/1',function(event, ui){
    $('#GUDANG_ASAL').val(ui.item.KODE);
    $('#GUDANG_ASAL2').val(ui.item.NAMA);    
  });

  autocomplete('GUDANG_TUJUAN','/autocomplete/status/reff_gudang/nama/2',function(event, ui){    
    $('#GUDANG_TUJUAN').val(ui.item.KODE);
    $('#GUDANG_TUJUAN2').val(ui.item.NAMA);
  });

autocomplete('NAMA_KAPAL','/autocomplete/status/reff_kapal/ship_name',function(event, ui){
    event.preventDefault();
    $('#NAMA_KAPAL').val(ui.item.NAMA);
    $('#CALL_SIGN').val(ui.item.CALLSIGN);
  });
});
</script> 