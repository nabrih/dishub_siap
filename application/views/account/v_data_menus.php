<div id="page-wrapper">
    <div class="col-md-12">
        <div><a href="<?= base_url()?>account">Account </a>  > <a href="<?= base_url()?>auth/groups/">Groups </a> > Menu</div>
        <div class="text-center">
            <h1>Data menu</h1>
            <div id="infoMessage"><?php echo $this->session->flashdata('message');?></div>
        </div>

        <style type="text/css">
            .badge-warning{
                background: #ffb22b;
            }
            .badge-success{
                background: #449d44;
            }

        </style>
       

        <a href="<?= base_url().'auth/create_menu' ?>">
        <button type="button" class="btn btn-success btn-rounded btn-xs"  title="Create New Menu" ><span class="fa fa-edit"></span> Create Menu</button></a>
        
        <div class="table-responsive" style="margin-top: 20px;">
            <table class="table" >
                <thead>
                    <th width="20">No.</th>
                    <!-- <th title="Name to be parameter of check">Name</th> -->
                    <th>Label</th>
                    <th width="100">Status</th>
                    <th width="100">As Parent</th>
                    <th width="100">Child of</th>
                    <th>URL</th>
                    <th>Created By</th>
                    <th>Created Time</th>
                    <th width="140"></th>
                </thead>
                <tbody>
                    
                         
            <?php if ($this->ion_auth->is_admin()):
                $no = 1;
                foreach ($menus as $menu):

                    $menu_id  = $menu['menu_id'];
                    $name = $menu["menu_desc"];
                    $status = $menu["status"];
                    $ashead = $menu["ashead"];
                    $parent_name = $menu["parent_name"];
                    $url = $menu["url"];
                    $created_by = $menu["created_by"];
                    $created_time = $menu["created_time"];

                    echo '<tr data-id="'.$menu_id.'" data-name="'.$name.'" >';

                    echo '<td>'.($no++).'</td>';
                    echo '<td>'.$name.'</td>';
                    echo "<td><span class='badge ".($status==0?'badge-warning':'badge-success')."'>".($status==0?'Inactive':'Activated')."</span></td>";
                    echo "<td><span class='badge ".($ashead==0?'badge-warning':'badge-success')."'>".($ashead==0?'No':'Yes')."</span></td>";
                    echo '<td>'.$parent_name.'</td>';
                    echo '<td>'.$url.'</td>';
                    echo '<td>'.$created_by.'</td>';
                    echo '<td>'.$created_time.'</td>';
                    echo '<td> <a href="'.base_url()."auth/edit_menu/".$menu_id.'"><button type="button" class="btn btn-success btn-rounded m-b-10 m-l-5 btn-xs"  title="Edit data"><span class="fa fa-edit"></span></button></a>';
                    echo '<button type="button" class="btn btn-danger btn-rounded m-b-10 m-l-5 btn-xs delMenu"  title="Delete"><span class="fa fa-trash-o"></span></button> </td>';

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
        $('.delMenu').on('click',function() {
            var id = $(this).closest('tr').data('id');
            var name = $(this).closest('tr').data('name');
            var y = confirm('Delete menu '+name+'?');

            if (y) {
                var url = "<?= base_url() ?>auth/delete_menu/"+id;
                var check = 'success';
                get(url, check, function () {
                    window.location = '<?= current_url() ?>';
                });
            }
        });

    </script>
</div>