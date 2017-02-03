<div class="card">
  <div class="card-block p-a-0">
    <div class="box-tab m-b-0" id="rootwizard">
      <ul class="wizard-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab">UBAH STATUS</a></li>
       <li class="active"><a href="#tab1" data-toggle="tab">DETAIL</a></li>
      </ul>
      <div class="tab-content" style="overflow: auto">
        <div class="tab-pane p-x-lg active" id="tab1" style="overflow: auto">
         <!-- FORM HERE -->
            <table >
              <tr><td width="20%">No Ubah Status *</td><td width="30%"><?php echo $arrhdr['NO_UBAH_STATUS']; ?></td><td width="20%">Tgl. Ubah Status</td><td><?php echo date_input($arrhdr['TGL_UBAH_STATUS']); ?></td></tr>
              <tr><td>Gudang Asal *</td><td colspan="2"><?php echo $arrhdr['GUDANGASAL']; ?></td></tr>
              <tr><td>Gudang Tujuan *</td><td colspan="2"><?php echo $arrhdr['GUDANGTUJUAN']; ?></td></tr>
              <tr><td>Nama Pemohon *</td><td><?php echo $arrhdr['NM_LENGKAP'] ?></td><td>Status</td><td><?php echo $arrhdr['STATUS']; ?></td></tr>
              <tr><td>Nama Kapal *</td><td><?php echo $arrhdr['NAMA_KAPAL']; ?></td><td>No. VOY FLIGHT *</td><td><?php echo $arrhdr['NO_VOY_FLIGHT']; ?></td></tr>
              <tr><td>Call Sign *</td><td><?php echo $arrhdr['CALL_SIGN']; ?></td><td>Tgl. Tiba *</td><td><?php echo $arrhdr['TGL_TIBA']; ?></td></tr>
              <tr><td>No. BC 1.1 *</td><td><?php echo $arrhdr['NO_BC11']; ?></td><td>Tgl. BC 1.1 *</td><td><?php echo $arrhdr['TGL_BC11']; ?></td></tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
