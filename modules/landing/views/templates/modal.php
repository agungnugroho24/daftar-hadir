<!-- Login & Registrasi pop up -->
    <div class="lander hide"></div>
    <div class="login hide">
      <i class="fas fa-times" onclick="closeLander()"></i>
      <div class="icon"><img src="<?=base_url('assets/landing/image/png/logo-bappenas.png')?>" alt=""></div>
      <div class="text-center">Login</div>      
      <hr>    
      <!-- <h3 class="mb-5 text-center">Login</h3> -->
      <!-- <form id="form_login" action="user.html"> -->
        <div class="input-group mb-5">
          <div class="input-group-prepend">
            <span class="input-group-text input-underline"><i class="fas fa-user-friends"></i></span>
          </div>
          <input type="email" id="email_login" class="form-control form-underline"placeholder="Email">
        </div>
        <div class="input-group mb-5">
          <div class="input-group-prepend">
            <span class="input-group-text input-underline"><i class="fas fa-lock"></i></span>
          </div>
          <input type="password" id="pass_login" class="form-control form-underline" placeholder="Kata sandi">
        </div>
        <button type="submit" class="btn btn-block btn-lg-1 btn-dodgerblue border border-dodgerblue font-size-5 letter-spacing-n1 mt-5 mb-5" onclick="userLogin()" id="login">Login</button>
        Belum memiliki akun? <a href="#" onclick="lR()">Registrasi</a>
        <div>Lupa password. <a href="<?=base_url('contactus')?>">Klik di sini</a></div>
      <!-- </form> -->
    </div>

    <div class="registrasi hide">
      <i class="fas fa-times" onclick="closeLander()"></i>
      <div class="icon"><img src="<?=base_url('assets/landing/image/png/logo-bappenas.png')?>" alt=""></div>
      <!-- <h3 class="text-center">Registrasi</h3> -->
      <!-- <form id="form_register"> -->
        <div class="input-group mb-5">
          <div class="input-group-prepend">
            <span class="input-group-text input-underline"><i class="fas fa-user-friends"></i></span>
          </div>
          <input type="email" id="email" class="form-control form-underline"placeholder="Email">
        </div>
        <div class="input-group mb-5">
          <div class="input-group-prepend">
            <span class="input-group-text input-underline"><i class="fas fa-lock"></i></span>
          </div> 
          <input type="password" id="password" class="form-control form-underline" placeholder="Kata sandi">
        </div>
        <div class="input-group mb-5">
          <div class="input-group-prepend">
            <span class="input-group-text input-underline"><i class="fas fa-key"></i></span>
          </div>
          <input type="password" id="confPassword" class="form-control form-underline" placeholder="Konfirmasi kata sandi">
        </div>
        <button id="send" type="submit" onclick="submit()" class="btn btn-block btn-lg-1 btn-dodgerblue border border-dodgerblue font-size-5 letter-spacing-n1 mt-5 mb-5">Registrasi</button>
        Sudah memiliki akun? <a href="#" onclick="rL()">Login</a>
      <!-- </form> -->
    </div>

<script type="text/javascript">
  

  function submit() {
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confPassword = document.getElementById("confPassword").value;
    if (validateEmail(email)!=true) {
        swal("Error", "Email tidak sesuai", "error");
    }else{
      if (password!=confPassword) {
        swal("Error", "Kata sandi dan konfirmasi kata sandi tidak sesuai", "error");
      }else if(password.length<6){
        swal("Error", "Kata sandi minimal 6 karakter", "error");
      }else{
        if (email!='' && email!=null && password!='' && password!='') {
          $.ajax({
              data: {email:email,password:password,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
              url: "<?=base_url('landing/data/register')?>",
              method: "POST",
              dataType: 'html',
              beforeSend: function() { 
                $('#send').attr('disabled','disabled');
                $('#send').text('Loading ...');
              },
              success: function(data) {
                  if (data=="1") {
                    closeLander();
                    swal("Berhasil", "Register berhasil. Silahkan cek email anda untuk melakukan aktivasi", "success");
                  }else if (data=="2") {                  
                    swal("Error", "User telah terdaftar", "error");
                    $('#send').prop("disabled", false);
                    $('#send').text('Simpan');
                  }else{
                    swal("Error", "Tambah data gagal, Mohon Coba Lagi", "error");
                    $('#send').prop("disabled", false);
                    $('#send').text('Simpan');
                  }
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  console.log(xhr.status);
                  console.log(thrownError);
              }
          });
        }else{
          swal("Error", "Data tidak boleh kosong", "error");
        }
      }
    }
  }

  function validateEmail(mail) 
  {
    if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail))
    {
      return true;
    }

    return false;
  }

  function userLogin() {
    var email = document.getElementById("email_login").value;
    var password = document.getElementById("pass_login").value;
    if (email!='' && email!=null && password!='' && password!='') {
      $.ajax({
          data: {email:email,password:password,<?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'},
          url: "<?=base_url('landing/data/login')?>",
          method: "POST",
          dataType: 'html',
          beforeSend: function() { 
            $('#login').attr('disabled','disabled');
            $('#login').text('Loading ...');
          },
          success: function(data) {
            if (data=='1') {   
              window.location = '<?php echo base_url('user'); ?>';          
            }else if(data=='2'){ 
              swal("Peringatan", "Mohon aktivasi akun anda", "warning");
              $('#login').prop("disabled", false);
              $('#login').text('Simpan');
            }else if (data=="3") {                  
              swal("Error", "Periode pendaftaran sudah berakhir atau belum dibuka", "error");
              $('#login').prop("disabled", false);
              $('#login').text('Simpan');
            }else{
              swal("Error", "Email atau Password Salah", "error");
              $('#login').prop("disabled", false);
              $('#login').text('Simpan');              
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
              console.log(xhr.status);
              console.log(thrownError);
          }
      });
    }else{
      swal("Error", "Data tidak boleh kosong", "error");
    }
  }
</script>