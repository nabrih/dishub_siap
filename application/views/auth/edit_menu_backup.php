<div id="page-wrapper">
	<div id="infoMessage"><?php echo $this->session->flashdata('message');?></div>
	<div class="col-md-12">
		<div><a href="<?= base_url()?>jxpanel/account">Account </a>  > <a href="<?= base_url()?>auth/groups/">Groups </a> > <a href="<?= base_url()?>auth/menus/">Menu </a> > Edit Menu</div>

		<div class="text-center">
				<h1>Edit Menu</h1>
		</div>
		<p>Please enter the menu information below.</p>
		<?php echo form_open("auth/edit_menu/".$menu->menu_id);?>
			<input type="text" name="id" hidden="hidden" value="<?= $menu->menu_id; ?>">
		      <p>
		            <?php echo "Menu Name"?> <br />
		            <?php echo form_input($menu_name);?>
		      </p>

		      <p><?php echo form_submit('submit', 'Update Menu', 'class="btn btn-info"');?></p>

		<?php echo form_close(); ?>

	</div>
</div>