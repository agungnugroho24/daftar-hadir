          <div class="navbar-header">
				<a href="<?=base_url()?>" class="logo">
      				<img src="<?=base_url('assets/landing/img/logo3.gif')?>" class="img-fluid" style="height:50px;width:140px;margin-top: -8%;">
      			</a>
              <!--logo end-->
				<?php if($this->session->userdata('role')== "1"){?>
      			<div class="sidebar-toggle-box">
                    <div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-bars tooltips"></div>
      			</div>
				<?php }?>
			     <a href="<?=base_url('admin/data')?>" class="logo">DAFTAR HADIR</a>
          </div>
          <!--logo end-->
          <div class="top-nav ">
              <ul class="nav pull-right top-menu">
                  <!-- <li>
                      <input type="text" class="form-control search" placeholder="Search">
                  </li> -->
                  <!-- user login dropdown start-->
                  <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <i class="fa fa-user" style="padding:5px; color:#fff; background:#3F51B5;"></i>
                          <span class="username"><? if($this->session->userdata('name')!=""){ echo $this->session->userdata('name');}else{ echo 'user';}?></span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                          <!-- <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                          <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                          <li><a href="#"><i class="fa fa-bell-o"></i> Notification</a></li> -->
                            <div class="text-center">
                                <li>
                                    <a href="<?=base_url()?>" style="font-size: 15px;"><i class=" fa fa-home"></i> Landing Page</a>
                                </li>
                            </div>
                          <li><a href="<?=base_url('admin/logout')?>"><i class="fa fa-key"></i> Log Out</a></li>
                      </ul>
                  </li>
                  <!-- user login dropdown end -->
              </ul>
          </div>