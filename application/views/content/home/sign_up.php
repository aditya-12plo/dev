<div class="app signup usersession">
  <div class="session-wrapper">
        <div class="card bg-white no-border">
          <div class="card-block">
            <div class="text-center m-b">
              <h3 class="text-uppercase">CFS-CENTER</h3>
              <p>DAFTAR USER BARU</p>
            </div>
            <?php if($this->session->flashdata('result_error')){
      				$result_er=$this->session->flashdata('result_error');
      				echo "<div class='alert alert-danger' >".$result_er."</div>";
      			}
      			if($this->session->flashdata('result')){
      				$result_r=$this->session->flashdata('result');
      				echo "<div class='alert alert-success' >".$result_r."</div>";
      			}
            ?>
            <form id="formlogin" class="form-horizontal" method="post" autocomplete="off" action="<?php echo site_url(); ?>/home/sign_up_cek">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label-left col-sm-6 text-uppercase"><b>DATA PERUSAHAAN</b></label>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NAMA PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <input name="kd_company" id="kd_company" type="hidden" class="form-control input-lg">
                    <input name="company" id="company" type="text" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">ALAMAT PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <textarea class="form-control input-lg" name="alamatcompany" id="alamatcompany" required></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">EMAIL PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <input name="emailcompany" id="emailcompany" type="email" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NPWP PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <input name="npwpcompany" id="npwpcompany" type="text" class="form-control input-lg number" maxlength="15" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NO TELPON PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <input name="telponcompany" id="telponcompany" type="text" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NO FAX PERUSAHAAN</label>
                  <div class="col-sm-8">
                    <input name="faxcompany" id="faxcompany" type="text" class="form-control input-lg " required>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-1 text-uppercase"></label>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label-left col-sm-6 text-uppercase"><b>DATA DIRI</b></label>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NAMA LENGKAP</label>
                  <div class="col-sm-8">
                    <input name="fullname" id="fullname" type="text" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">USERNAME</label>
                  <div class="col-sm-8">
                    <input name="username" id="username" type="text" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">EMAIL</label>
                  <div class="col-sm-8">
                    <input name="email" id="email" type="email" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">NO HANDPHONE</label>
                  <div class="col-sm-8">
                    <input name="telpon" id="telpon" type="text" class="form-control input-lg " required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">PASSWORD</label>
                  <div class="col-sm-8">
                    <input name="password" id="password" type="password" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">KONFIRMASI PASSWORD</label>
                  <div class="col-sm-8">
                    <input name="conf_password" id="conf_password" type="password" class="form-control input-lg" required>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label-left col-sm-4 text-uppercase">ROLE</label>
                  <div class="col-sm-8">
                    <select class="form-control input-lg" name="role">
                      <option value="CONS">CONSIGNEE</option>
                      <option value="FWD">FORWARDER</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-1 text-uppercase"></label>
              </div>
              <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                  <button class="btn btn-primary btn-block btn-lg m-b" type="submit">DAFTAR</button>
                </div>
              </div>
            </form>
            <div class="row">
      				<div class="pull-left">
      				  <a href="<?php echo site_url(); ?>" class="text-uppercase text-primary">Login</a>
      				</div>
    			  </div>
          </div>
  		  </div>
      </div>
    </div>
<script>
  $(function(){
      autocomplete('company','/autocomplete/signup_cek/organisasi/nama',function(event, ui){
      $('#kd_company').val(ui.item.KODE);
      $('#company').val(ui.item.NAMA);
      $('#alamatcompany').val(ui.item.ALAMAT);
      $('#emailcompany').val(ui.item.EMAIL);
      $('#npwpcompany').val(ui.item.NPWP);
      $('#telponcompany').val(ui.item.NOTELP);
      $('#faxcompany').val(ui.item.NOFAX);
    });
  });
  jQuery.validator.addMethod("noSpace", function(value, element) {
    return value.indexOf(" ") < 0 && value !== "";
  }, "Tidak boleh menggunakan spasi");
  $("#formlogin").validate({
			rules: {
        username: {
					noSpace: true,
          remote: {
            url: "<?php echo site_url();?>/home/uname",
            type: "post",
            data: {
              login: function(){
                return $('#formlogin :input[name="username"]').val();
              }
            }
          }
				},
        email: {
          remote: {
            url: "<?php echo site_url();?>/home/umail",
            type: "post",
            data: {
              login: function(){
                return $('#formlogin :input[name="email"]').val();
              }
            }
          }
				},
				conf_password: {
					equalTo: "#password"
				},
				npwpcompany: {
					minlength: 15
				}
      },
      messages:{
        username:{
         remote: jQuery.validator.format("username sudah terdaftar.")
       },
         email:{
          remote: jQuery.validator.format("email sudah terdaftar.")
          }
       }
    });
</script>
