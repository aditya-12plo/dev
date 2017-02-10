    <link href="<?php echo base_url();?>assets/css/jquery.tagit.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>assets/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
 <script src="<?php echo base_url();?>assets/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
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
              <label class="col-sm-3 control-label-left">Terminal *</label>
              <div class="col-sm-9"> 
                <input type="text" value="<?=$arrhdr['GUDANGASAL'];?>"  wajib="yes" id="GUDANG_ASAL" class="form-control" placeholder="TERMINAL"> 
                <input type="hidden" name="GUDANG_ASAL2" value="<?=$arrhdr['KD_GUDANG_ASAL'];?>" wajib="yes" id="GUDANG_ASAL2" class="form-control" placeholder="GUDANG ASAL2">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label-left">Warehouse *</label>
              <div class="col-sm-9"> 
                <input type="text" value="<?=$arrhdr['GUDANGTUJUAN'];?>" value="" wajib="yes" id="GUDANG_TUJUAN" class="form-control" placeholder="WAREHOUSE">
                <input type="hidden" name="GUDANG_TUJUAN2" value="<?=$arrhdr['KD_GUDANG_TUJUAN'];?>" wajib="yes" id="GUDANG_TUJUAN2" class="form-control" placeholder="GUDANG TUJUAN">
              </div>
            </div>
            <br><br>
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
                  <input type="text" name="CALL_SIGN" value="<?php echo $arrhdr['CALL_SIGN']; ?>" id="CALL_SIGN" class="form-control" placeholder="CALL SIGN">
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
<br><br>
                        <div class="form-group">
              <label class="col-sm-3 control-label-left">No Kontainer *</label>              
              <div class="col-sm-9"> 
              
            
  <button type="button" class="btn btn-primary btn-icon" id="addButton"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
<br><br>
<?php
if($act == "update")
{
if($num_rows > 0)
{
foreach ($arrcont as $key) {
 echo '<div class="form-inline"> <input type="text" name="NO_CONT[]" id="NO_CONT1" value="'.$key->NO_CONT.'" wajib="yes" class="rank form-control" placeholder="NO KONTAINER" >&nbsp; 
     <input type="text" name="UKURAN_CONT[]" id="UKURAN_CONT1" value="'.$key->UKURAN.'" wajib="yes" class="rank form-control" placeholder="UKURAN KONTAINER" >&nbsp;<button type="button" class="remove btn btn-danger" id="removeButton">Hapus</button></div><br>
';
}
}
else
{
    echo '<div class="form-inline">
  <input type="text" name="NO_CONT[]" id="NO_CONT1" wajib="yes" class="rank form-control" placeholder="NO KONTAINER" >
   <input type="text" name="UKURAN_CONT[]" id="UKURAN_CONT1" wajib="yes" class="rank form-control" placeholder="UKURAN KONTAINER" >
</div>';
}
}
else
{
  echo '<div class="form-inline">
  <input type="text" name="NO_CONT[]" id="NO_CONT1" wajib="yes" class="rank form-control" placeholder="NO KONTAINER" >
   <input type="text" name="UKURAN_CONT[]" id="UKURAN_CONT1" wajib="yes" class="rank form-control" placeholder="UKURAN KONTAINER" >
</div>';
}

?>

<br>
<div id="TextBoxContainer">
    <!--Textboxes will be added here -->
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


$(function () {
    $("#addButton").bind("click", function () {
        var div = $("<div />");
        div.html(GetDynamicTextBox(""));
        $("#TextBoxContainer").append(div);
    });
    $("body").on("click", ".remove", function () {
        $(this).closest("div").remove();
    });
});
function GetDynamicTextBox() {
    return '<div class="form-inline"> <input type="text" name="NO_CONT[]" id="NO_CONT1" wajib="yes" class="rank form-control" placeholder="NO KONTAINER" >&nbsp;' 
    + ' <input type="text" name="UKURAN_CONT[]" id="UKURAN_CONT1" wajib="yes" class="rank form-control" placeholder="UKURAN KONTAINER" >&nbsp;' +'<button type="button" class="remove btn btn-danger" id="removeButton">Hapus</button></div><br>'
}



/*
 $(function () {
    $("#addButton").bind("click", function () {
        var div = $("<div />");
        div.html(GetDynamicTextBox(""));
        $("#TextBoxContainer").append(div);
    });
    $("body").on("click", ".remove", function () {
        $(this).closest("div").remove();
    });
});
function GetDynamicTextBox() {
    return '<div class="form-inline">' +
 '<input type="text" name="NO_CONT[]" id="NO_CONT1" wajib="yes" class="form-control" placeholder="NO KONTAINER" >
  &nbsp;'+ '<input type="text" name="UKURAN_CONT[]" id="UKURAN_CONT1" wajib="yes" class="form-control" placeholder="UKURAN KONTAINER" >
&nbsp;' +
'<button type="button" class="remove btn btn-primary btn-icon" id="removeButton"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
</div>'
}


<input type="hidden" maxlength="11" class="form-control" name="NO_CONT" id="NO_CONT" value=""  wajib="yes">
<ul id="singleFieldTags"></ul>     

  $(function(){
 var sampleTags = "";
 $('#myTags').tagit();
 $('#singleFieldTags').tagit({
availableTags: sampleTags,
singleField: true,
singleFieldNode: $('#NO_CONT')
            }); $('#singleFieldTags2').tagit({
                availableTags: sampleTags
            }); $('#myULTags').tagit({
                availableTags: sampleTags, itemName: 'item',
                fieldName: 'tags'
            });var eventTags = $('#eventTags');

            var addEvent = function(text) {
                $('#events_container').append(text + '<br>');
            };

            eventTags.tagit({
                availableTags: sampleTags,
                beforeTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('beforeTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                afterTagAdded: function(evt, ui) {
                    if (!ui.duringInitialization) {
                        addEvent('afterTagAdded: ' + eventTags.tagit('tagLabel', ui.tag));
                    }
                },
                beforeTagRemoved: function(evt, ui) {
                    addEvent('beforeTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                afterTagRemoved: function(evt, ui) {
                    addEvent('afterTagRemoved: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagClicked: function(evt, ui) {
                    addEvent('onTagClicked: ' + eventTags.tagit('tagLabel', ui.tag));
                },
                onTagExists: function(evt, ui) {
                    addEvent('onTagExists: ' + eventTags.tagit('tagLabel', ui.existingTag));
                }
            });

            //-------------------------------
            // Read-only
            //-------------------------------
            $('#readOnlyTags').tagit({
                readOnly: true
            });

            //-------------------------------
            // Tag-it methods
            //-------------------------------
            $('#methodTags').tagit({
                availableTags: sampleTags
            });

            //-------------------------------
            // Allow spaces without quotes.
            //-------------------------------
            $('#allowSpacesTags').tagit({
                availableTags: sampleTags,
                allowSpaces: true
            });

            //-------------------------------
            // Remove confirmation
            //-------------------------------
            $('#removeConfirmationTags').tagit({
                availableTags: sampleTags,
                removeConfirmation: true
            });
            
        });
    */
</script> 

