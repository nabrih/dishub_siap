<div id="page-wrapper">
    <div class="col-md-12">
        <div><a href="<?= base_url()?>account">Account </a>  > Groups</div>
        <div class="text-center">
            <h1>Groups</h1>
            <div id="infoMessage"><?php echo $this->session->flashdata('message');?></div>
        </div>

        <a href="<?= base_url().'auth/create_group' ?>">
        <button type="button" class="btn btn-success btn-rounded"  title="Create New Group" ><span class="fa fa-group"></span> Create Group</button></a>
        <a href="<?= base_url().'auth/menus' ?>">
        <button type="button" class="btn btn-danger btn-rounded"  title="All data menu" ><span class="fa fa-bars"></span> List Menu</button></a>
        

        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table" >
                <thead>
                    <th width="20">No.</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th></th>
                </thead>
                <tbody>
                    
                         
            <?php if ($this->ion_auth->is_admin()):
                $no = 1;
                foreach ($groups as $group):

                    $gID  = $group['id'];
                    $name = $group["name"];
                    $desc = $group["description"];

                    echo '<tr data-id="'.$gID.'" data-name="'.$name.'" >';

                    echo '<td>'.($no++).'</td>';
                    echo '<td>'.$name.'</td>';
                    echo '<td>'.$desc.'</td>';
                    echo '<td> <a href="'.base_url()."auth/reg_group_menu/".$gID.'"><button type="button" class="btn btn-default btn-rounded m-b-10 m-l-5"  title="Registering menus into group"><span class="fa fa-bars"></span></button></a>';
                    echo '<a href="'.base_url()."auth/edit_group/".$gID.'"><button type="button" class="btn btn-success btn-rounded m-b-10 m-l-5"  title="Edit data"><span class="fa fa-edit"></span></button></a>';
                    echo '<button type="button" class="btn btn-danger btn-rounded m-b-10 m-l-5 delGroup"  title="Delete"><span class="fa fa-trash-o"></span></button> </td>';

                    ?>
                     
                    </tr>

                    <?php 


                endforeach;
                
             endif; ?>

                </tbody>
            </table>
        </div>

    </div>

     <script type="text/javascript">
        $('.delGroup').on('click',function() {
            var id = $(this).closest('tr').data('id');
            var name = $(this).closest('tr').data('name');
            var y = confirm('Delete group '+name+'?');

            if (y) {
                var url = "<?= base_url() ?>auth/delete_group/"+id;
                var check = 'success';
                get(url, check, function () {
                    window.location = '<?= current_url() ?>';
                });
            }
        });

    </script>
</div>