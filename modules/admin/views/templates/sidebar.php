
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
                  <li>
                      <a  href="<?=base_url('admin/data') ?>">
                          <i class="fa fa-dashboard"></i>
                          <span>Daftar Kegiatan</span>
                      </a>
                  </li>
				  <?php if($this->session->userdata('role') == "1"){?>
                  <li>
                      <a  href="<?=base_url('admin/users') ?>">
                          <i class="fa fa-user"></i>
                          <span>Daftar Admin</span>
                      </a>
                  </li>
				  <?php }?>
              </ul>
              <!-- sidebar menu end-->
          </div>