        <div id="page-wrapper">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Account</h3>
                    <div id="infoMessage"><?php echo $this->session->flashdata('message')?$this->session->flashdata('message'):"";?></div>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Account</li>
                    </ol>
                </div>

                <!-- Start Page Content -->
                <div class="col-lg-12" style="min-height: 300px;">
                
                    <div class="card">
                        <div class="card-content">
                            <a href="<?= base_url().'auth/create_user' ?>">
                            <button type="button" class="btn btn-info btn-rounded"  title="Create New Account" ><span class="fa fa-plus"></span> Create Account</button></a>

                            <a href="<?= base_url().'auth/groups' ?>">
                            <button type="button" class="btn btn-success btn-rounded"  title="View all group" ><span class="fa fa-group"></span> Group Data</button></a>
                           
                            <div class="table-responsive" style="margin-top: 20px;">
                                <table class="table">
                                    <thead>
                                        <th>No.</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Group</th>
                                        <th>Status</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $no = 1;
                                            foreach ($users as $row){
                                                $id             = $row->id;
                                                $full_name      = $row->full_name;
                                                $username       = $row->username;
                                                $email          = $row->email;
                                                $active         = $row->active;
                                                $groups         = $row->groups;
                                            
                                        ?>
                                        <tr data-account-id="<?= $id ?>" data-account-name="<?= $full_name ?>" >
                                            <td><?= $no++ ?></td>
                                            <td><?= $full_name ?></td>
                                            <td><?= $username ?></td>
                                            <td><?= $email ?></td>
                                            <td>
                                                <?php foreach ($groups as $group):?>
                                                    <?php //  echo anchor(base_url()."auth/edit_group/".$group->id, htmlspecialchars($group->name,ENT_QUOTES,'UTF-8')) ;?>
                                                    <?= $group->name ?><br />
                                                <?php endforeach?>
                                            </td>
                                            <td><?php echo ($active) ? anchor(base_url()."auth/deactivate/".$id, 'active') : anchor(base_url()."auth/activate/". $id, 'not active');?></td>

                                            <td>
                                                <a href="<?= base_url().'auth/edit_user/'.$id ?>"><button type="button" class="btn btn-success btn-rounded m-b-10 m-l-5"  title="Edit data"><span class="fa fa-edit"></span></button></a>
                                                
                                                <button type="button" class="btn btn-danger btn-rounded m-b-10 m-l-5 delAccount"  title="Delete"><span class="fa fa-trash-o"></span></button>
                                            </td>
                                        </tr>
                                        <?php
                                            }
                                        ?>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End PAge Content -->
            </div>
            <!-- End Bread crumb -->

            <script type="text/javascript">
                $('.delAccount').on('click',function() {
                    var id = $(this).closest('tr').data('account-id');
                    var account_name = $(this).closest('tr').data('account-name');
                    var y = confirm('Delete account '+account_name+'?');

                    if (y) {
                        var url = "<?= base_url() ?>api/a_account/delete/"+id;
                        var check = 'success';
                        get(url, check, function () {
                            window.location = "<?= base_url() ?>account";
                        });
                    }
                });

            </script>
</div>