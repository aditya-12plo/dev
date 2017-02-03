<div class="header navbar">
  <div class="brand visible-xs">
    <div class="toggle-offscreen"> <a href="javascript:;" class="hamburger-icon visible-xs" data-toggle="offscreen" data-move="ltr"> <span></span> <span></span> <span></span> </a> </div>
    <a class="brand-logo"> <span>CFS CENTER</span> </a> </div>
  <ul class="nav navbar-nav navbar-right hidden-xs">
    <li> <a href="javascript:;" class="ripple" data-toggle="dropdown"><span><?php echo ucwords(strtolower($this->newsession->userdata('NM_GROUP')))." , ".$this->newsession->userdata('NM_LENGKAP'); ?></span> <span class="caret"></span> </a>
      <ul class="dropdown-menu">
        <li> <a href="<?php echo site_url('dashboard/signout'); ?>">Logout</a> </li>
      </ul>
    </li>
  </ul>
</div>
