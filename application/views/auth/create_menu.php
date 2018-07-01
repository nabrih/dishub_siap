<div id="page-wrapper">
	
	<div id="infoMessage"><?php echo $message;?></div>
	<div class="col-md-12">
		<div><a href="<?= base_url()?>jxpanel/account">Account </a>  > <a href="<?= base_url()?>auth/groups/">Groups </a> > <a href="<?= base_url()?>auth/menus/">Menu </a> > Create Menu</div>

		<div class="text-center">
				<h1>Create Menu</h1>
		</div>
		<div class="col-md-6">
			<p>Please enter the menu information below.</p>
			<?php echo form_open("auth/create_menu");?>

			      <p>
			            <?php echo "Menu Name (parameter)"?> <br />
			            <?php echo form_input($menu_name);?>
			      </p>
			      <p>
			            <?php echo "Description (show in menu)"?> <br />
			            <?php echo form_input($menu_desc);?>
			      </p>
			      <p>
			            URL<br />
			            <?php echo form_input($url);?>
			      </p>
			      <p>
			            Icon<br />
			            <?php echo form_dropdown($icon_name);?>
			      </p>
			      <p>
			            Status<br />
			            <?php echo form_dropdown($status);?>
			      </p>

			      <p><?php echo form_submit('submit', 'Create Menu', 'class="btn btn-info"');?></p>

			<?php echo form_close(); ?>
		</div>
	</div>
</div>