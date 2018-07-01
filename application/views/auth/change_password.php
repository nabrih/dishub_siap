            <div id="infoMessage"><?php echo $message;?></div>

            <?php 
            $attr_form = array('class' => 'form-horizontal form-label-left' );
            $attr_input = array('class'=>'form-control');

            echo form_open("", $attr_form);?>

                  <div class="form-group">
                        <label>Old Password</label>
                        <?php echo form_input($old_password,'', $attr_input);?>
                  </div>

                  <div class="form-group">
                        <label>New Password (min : <?= $min_password_length ?>)</label>
                        <?php echo form_input($new_password,'',$attr_input);?>
                  </div>

                  <div class="form-group">
                        <label>Confirm</label>                
                        <?php echo form_input($new_password_confirm,'',$attr_input);?>
                  </div>

                  <!-- <p><?php echo form_submit('submit', lang('change_password_submit_btn'));?></p> -->
                  <button type="button" class="btn btn-info" onclick="change_password()">Change</button>

            <?php echo form_close();?>

             <script type="text/javascript">
                  function change_password() {                        
                        var data = {
                              <?= $csrf['name'] ?>: "<?= $csrf['hash'] ?>",
                              old: $('#old').val(),
                              new: $('#new').val()
                        };

                        if($('#new_confirm').val() !== data.new){
                              $("#errorMessage").html('Confirm password not fair');
                        }else{
                              post('<?= base_url() ?>api/a_account/change_password', data, 'success', function () {
                                    location.href = '<?= base_url() ?>auth/logout';
                              });
                        }
                  }
            </script>

            
   
