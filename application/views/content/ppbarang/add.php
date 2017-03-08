<div class="card">
  <div class="card-block p-a-0">
    <div class="box-tab m-b-0" id="rootwizard">
      <ul class="wizard-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">ENTRY</a></li>
        <li style="width:100%;"> <a data-toggle="" style="text-align:right">
          <button type="button" class="btn btn-primary btn-icon" onclick="save_popup('form_data','divtblppbarang'); return false;">Save <i class="icon-check"></i></button>
          </a> </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane p-x-lg active" id="tab1">
          <form name="form_data" id="form_data" class="form-horizontal" role="form" action="<?php echo site_url('ppbarang/execute/'.$act.'/sppb/'.$id); ?>" method="post" autocomplete="off" onsubmit="save_popup('form_data','divtblppbarang'); return false;">
            <div class="form-group">
              <label class="col-sm-3 control-label-left" >NO SPPB</label>
              <div class="col-sm-5">
                <input type="hidden" name="DATA[CAR]" value="">
                <input type="text" class="form-control" name="NO_SPPB" id="NO_SPPB" wajib="yes" placeholder="NO SPPB" value="" maxlength="50">
              </div>
              <div class="col-sm-4">
                <div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="icon-calendar"></i></span>
                  <input class="form-control drp" type="text" value=""  placeholder="TANGGAL SPPB" name="TGL_SPPB" id="TGL_SPPB" data-provide="datepicker" wajib="yes">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">NO PIB</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="NO_PIB" id="NO_PIB" wajib="yes" placeholder="NO PIB" value="" maxlength="50" >
              </div>
              <div class="col-sm-4">
                <div class="input-prepend input-group"><span class="add-on input-group-addon"><i class="icon-calendar"></i></span>
                  <input class="form-control drp" type="text" value=""  placeholder="TANGGAL PIB" name="TGL_PIB" id="TGL_PIB" data-provide="datepicker" wajib="yes">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">KD KPBC</label>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="KD_KPBC" id="KD_KPBC" wajib="yes" placeholder="KD KPBC" value="" maxlength="50">
              </div>
              <div class="col-sm-6">
                <input type="text" class="form-control" id="NM_KPBC" wajib="yes" placeholder="NM KPBC" value="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">NPWP IMPORTIR</label>
              <div class="col-sm-9">
                <input type="text" name="NPWP_IMP" value="" wajib="yes" id="NPWP_IMP" class="form-control" placeholder="NPWP IMPORTIR" maxlength="15">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">NAMA IMPORTIR</label>
              <div class="col-sm-9">
                <input type="text" name="NAMA_IMP" value="" wajib="yes" id="NAMA_IMP" class="form-control" placeholder="NAMA IMPORTIR" maxlength="50" >
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">NO BL</label>
              <div class="col-sm-9">
                <input type="text" name="NO_BL" value="" wajib="yes" id="NO_BL" class="form-control" placeholder="NO BL" maxlength="50" >
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">NAMA KAPAL</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="NAMA_KAPAL" id="NAMA_KAPAL" wajib="yes" placeholder="NAMA KAPAL" value="" maxlength="50">
              </div>
              <div class="col-sm-4">
                <input type="text" class="form-control" id="CALL_SIGN" placeholder="CALL_SIGN" value="">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">GUDANG</label>
              <div class="col-sm-9">
                <input type="text" class="form-control" name="GUDANG" id="GUDANG_TUJUAN2" wajib="yes" placeholder="GUDANG" value="" maxlength="50">
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
  date('drp');
  autocomplete('GUDANG_TUJUAN','/autocomplete/status/reff_gudang/nama/2',function(event, ui){
    $('#GUDANG_TUJUAN').val(ui.item.KODE);
    $('#GUDANG_TUJUAN2').val(ui.item.NAMA);
  });
  autocomplete('GUDANG_TUJUAN2','/autocomplete/status/reff_gudang/nama/2',function(event, ui){
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
