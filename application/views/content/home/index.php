<div class="app signin usersession">
  <div class="session-wrapper">
    <div class="page-height-o row-equal align-middle">
      <div class="column">
        <div class="card bg-white no-border">
          <div class="card-block">
          	<form id="form-login" class="form-layout" method="post" autocomplete="off" onSubmit="javascript:return login()" action="<?php echo site_url(); ?>/home/ceklogin/<?php echo $this->session->userdata('session_id'); ?>">
              <div class="text-center m-b">
                <h4 class="text-uppercase">CFS-CENTER</h4>
                <p>SILAKAN LOGIN</p>
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
              <div class="form-inputs">
                <label class="text-uppercase">EMAIL / USERNAME</label>
                <input name="username" id="username" type="text" class="form-control input-lg" placeholder="Email / Username">
                <label class="text-uppercase">PASSWORD</label>
                <input name="password" id="password" type="password" class="form-control input-lg" placeholder="Password" required>
              </div>
              <button class="btn btn-primary btn-block btn-lg m-b"type="submit">Login</button>
			  <div class="row">
				<div class="pull-left">
				  <a href="<?php echo site_url('home/sign_up'); ?>" class="text-uppercase text-primary">daftar</a>
				</div>
				<!-- /.col -->
				<div class="pull-right">
				  <a href="<?php echo site_url('home/forgot_password'); ?>" class="text-uppercase text-primary">lupa password?</a>
				</div>
				<!-- /.col -->
			  </div>
			</form>
		  </div>
        </div>
      </div>
    </div>
  </div>
</div>
