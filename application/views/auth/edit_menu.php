<div id="page-wrapper">
	<div id="infoMessage"><?php echo isset($message)?$message:"";?></div>
	<div class="row">
		<div><a href="<?= base_url()?>account">Account </a>  > <a href="<?= base_url()?>auth/groups/">Groups </a> > <a href="<?= base_url()?>auth/menus/">Menu </a> > Edit Menu</div>

		<div class="text-center">
				<h1>Edit Menu</h1>
		</div>
		<p>Please enter the menu information below.</p>
		<div class="col-md-6" >
		<?php echo form_open("auth/edit_menu/".$menu->menu_id);?>
			<input type="text" name="id" hidden="hidden" value="<?= $menu->menu_id; ?>">
		      <p>
		            <?php echo "Menu Name (parameter)"?> <br />
		            <?php echo form_input($menu_name);?>
		      </p>
		      <p>
		            <?php echo "Label (show in menu)"?> <br />
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
		      <p>
		            Set as Parent<br />
		            <?php echo form_dropdown($ashead);?>
		      </p>
		      <p>
		            Child of<br />
		            <?php echo form_dropdown($parent_id);?>
		      </p>

		      <p><?php echo form_submit('submit', 'Update Menu', 'class="btn btn-info"');?></p>

		<?php echo form_close(); ?>
		</div>

	</div>
</div>