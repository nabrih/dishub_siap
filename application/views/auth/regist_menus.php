<div id="page-wrapper">
	<div class="col-md-12">
		<div><a href="<?= base_url()?>jxpanel/account">Account </a> > <a href="<?= base_url()?>auth/groups/">Groups </a> > Register menu</div> 
		<div class="col-md-12 text-center">
			<h2>Registering menu into group (<?php echo $group->name; ?>)</h2>
			<div id="infoMessage"><?php echo $this->session->flashdata('message');?></div>
		</div>
		<style type="text/css">
			input[type="checkbox"]{
				margin-right: 10px;
			}
			.leftli li{
				float: left;
				margin-right: 20px;
			}
		</style>

		<div class="col-md-12">
		<ul class="leftli">
			<li><a rel="group_1" href="#select_all">Select All</a></li>
			<li><a rel="group_1" href="#select_none">Select None</a></li>
			<li><a rel="group_1" href="#invert_selection">Invert Selection</a></li>
		</ul><br/>
			
			<div class="col-md-12" id="group_1">
				
			<?php 
				echo form_open(current_url());

				?>
				<input type="group_id" name="group_id" value="<?= $group->id ?>" hidden="hidden">
				<?php
					$no = 1;
			        foreach ($menus as $row){
			            $menu_id = $row['menu_id'];
			            $menu_name = $row['menu_name'];
			            echo '<input type="checkbox" name="menus[]" value="'.$menu_id.'" ';
			            foreach ($reg_menus as $r_menu) {
			            	if ($menu_id == $r_menu['menu_id']) {
			            		echo 'checked="checked"';
			            	}
			            }
			            echo '>'.ucfirst($menu_name);

			            echo '<br/>';
			        }

				?>
				
				<div class="col-md-12">
					<button type="submit" class="btn btn-success">Save</button>
				</div>
			<?php echo form_close();?>
			</div>
		</div>
	</div>
		<script type="text/javascript">
	        // Select all
	        $("A[href='#select_all']").click( function() {
	            $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', true);
	            return false;
	        });
	        // Select none
	        $("A[href='#select_none']").click( function() {
	            $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").attr('checked', false);
	            return false;
	        });
	        // Invert selection
	        $("A[href='#invert_selection']").click( function() {
	            $("#" + $(this).attr('rel') + " INPUT[type='checkbox']").each( function() {
	                $(this).attr('checked', !$(this).attr('checked'));
	            });
	            return false;
	        });
		</script>
</div>