<div id="page-wrapper" style="padding-bottom: 10px; padding-top: 10px;">

  <div id="infoMessage"><?php echo $message;?></div>
  <div>
  <h1><?php echo lang('create_user_heading');?></h1>
  <p><?php echo lang('create_user_subheading');?></p>
  <?php echo form_open("auth/create_user");?>

        <p>
              <?php echo lang('create_user_fname_label', 'first_name');?> <br />
              <?php echo form_input($first_name);?>
        </p>

        <p>
              <?php echo lang('create_user_lname_label', 'last_name');?> <br />
              <?php echo form_input($last_name);?>
        </p>

        <p>
              <label for="full_name">Nama Lengkap</label> <br />
              <?php echo form_input($full_name);?>
        </p>
        
        <?php
        if($identity_column!=='email') {
            echo '<p>';
            // echo lang('create_user_identity_label', 'identity');
            echo '<label for="full_name">Username</label> <br />';
            echo form_error('identity');
            echo form_input($identity);
            echo '</p>';
        }
        ?>

        <p>
              <?php// echo lang('create_user_company_label', 'company');?> <!-- <br /> -->
              <?php echo form_input($company,'','hidden');?>
        </p>

        <!-- additional -->
        <p>
              <label for="nip">NIP</label> <br />
              <?php echo form_input($nip);?>
        </p>
        <p>
              NRK <br />
              <?php echo form_input($nrk);?>
        </p>
        <p>
              <label for="lokasi">Lokasi</label> <br />
              <?php echo form_dropdown($lokasi);?>
              <!-- <select name="lokasi" id="lokasi" class="form-control" style="width: 175px;">
                <option value="PG" selected="selected">PULO GADUNG</option>
                <option value="UM">UJUNG MENTENG</option>
                <option value="CL">CILINCING</option>
                <option value="KA">KEDAUNG ANGKE</option>
              </select> -->

        </p>
        <!-- additional end-->

        <p>
              <label for="email">Email</label> <br />
              <?php echo form_input($email);?>
        </p>

        <p>
              <label for="telepon">Telepon</label> <br />
              <?php echo form_input($phone);?>
        </p>

        <p>
              <label for="password">Password</label> <br />
              <?php echo form_input($password);?>
        </p>

        <p>
              <label for="password_confirm">Konfirmasi Password</label> <br />
              <?php echo form_input($password_confirm);?>
        </p>


        <p><?php echo form_submit('submit', lang('create_user_submit_btn'));?></p>

  <?php echo form_close();?>
  </div>
<div>