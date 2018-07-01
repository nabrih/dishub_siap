<?php 
    $page = isset($page)?$page:"home";
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Dishub">

    <title>SIAP - Sistem Administrasi Pengujian Kendaraan Bermotor</title>

    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/bootstrap-datepicker3.standalone.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/dashboard.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/jquery.alerts.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url(); ?>assets/css/plugins/dataTables.bootstrap.css" rel="stylesheet">
     <link href="<?php echo base_url(); ?>assets/css/plugins/fullCalender/fullcalendar.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/plugins/fullCalender/fullcalendar.print.min.css" rel="stylesheet" media="print">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="<?php echo base_url(); ?>assets/js/jquery-1.11.0.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/fullCalender/jquery-ui.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/fullCalender/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.id.min.js" charset="UTF-8"></script>
    <script src="<?php echo base_url(); ?>assets/js/dashboard.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/metisMenu/metisMenu.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.blockui.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.alerts.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/fullCalender/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/fullCalender/fullcalendar.min.js"></script>

    <style type="text/css">
        .page-wrapper{
            padding-bottom: 10px; padding-top: 10px;
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"> <img src="<?php echo base_url(); ?>assets/img/dishubx.png" height="26px" /> SIAP - Sistem Administrasi Pengujian Kendaraan Bermotor</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <span style="color:#f5f5f5"><?php setlocale(LC_TIME, array('id_ID', 'id_ID.utf8')); echo strftime('%A, %e %B %Y') ?></span>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <?php echo $this->session->userdata('nama') ?> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo site_url(); ?>auth/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <?php
                            $this->load->library(array('ion_auth'));
                            $acl = $this->ion_auth->allowed_menu(); // panggil fungsi
                        ?>


                        <li class="sidebar-search">
                            <span style="color:#e5e5e5;font-weight:bold">SIAP - Pulogadung</span>
                        </li>
                        <!-- <li>
                            <a <?php if($page == "home") echo "class='active'"; ?> href="<?php echo base_url(); ?>"><i class="fa fa-home fa-lg"></i> Home</a>
                        </li> -->

                        <?php 
                            $CI =& get_instance();
                            $CI->load->model('acl_model');
                            $result = $CI->acl_model->get_allowed_menu();

                            if (isset($result)) {
                             foreach ($result->result_array() as $row)
                             {
                                $result_child = $CI->acl_model->get_allowed_menu($row['menu_id']);

                                if ($result_child->num_rows()>0) {

                                    // find page in child
                                    $activeparent = false;
                                    foreach ($result_child->result_array() as $row_child_check){
                                        if ($row_child_check['menu_name']==$page) {
                                            $activeparent=true;
                                        }
                                    }

                                    //check parent active
                                    if ($activeparent) {
                                        echo '<li class="active">';
                                    }else{
                                    ?>
                                    <li <?php if($page == $row['menu_name']) echo "class='active'"; ?> >
                                    <?php } ?>

                                        <a href="<?= base_url().$row['url'] ?>"><i class="<?= $row['icon_name'] ?>"></i> <?= $row['menu_desc'] ?><?= $row['ashead']?'<span class="fa arrow"></span>':"" ?></a>


                                        <!-- loop child here -->
                                        <ul class="nav nav-second-level">
                                        <?php 
                                        foreach ($result_child->result_array() as $row_child)
                                        {
                                            ?>
                                                <li>
                                                    <a <?= ($page == $row_child['menu_name'])?"class='active'":"" ?> href="<?php echo site_url().$row_child['url']; ?>">
                                                    <i class="<?= $row_child['icon_name'] ?>"></i> <?= $row_child['menu_desc'] ?></a>
                                                </li>                
                                            <?php 
                                        }
                                        ?>
                                        </ul>
                                    </li>
                                    <?php
                                }else{
                                ?>
                                <li>
                                    <a href="<?= base_url().$row['url'] ?>"  <?php if($page == $row['menu_name']) echo "class='active'"; ?> > <i class="<?= $row['icon_name'] ?>"></i> <?= $row['menu_desc'] ?> </a>
                                </li>
                                <?php
                                }// child is 0
                             }
                            }
                        ?>

                        
                        
                        
                       
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
