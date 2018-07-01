<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" href="<?= base_url() ?>assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="32x32"  href="<?= base_url() ?>assets/img/favicon-32x32.png">

    <title><?= isset($title)?$title:"Admin" ?></title>

    <link href="<?= base_url() ?>assets/adm/css/lib/chartist/chartist.min.css" rel="stylesheet">
	<link href="<?= base_url() ?>assets/adm/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>assets/adm/css/lib/owl.theme.default.min.css" rel="stylesheet" />

    <link href="<?= base_url() ?>assets/adm/css/lib/nestable/nestable.css" rel="stylesheet">
    
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url() ?>assets/adm/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">

    <link href="<?= base_url() ?>assets/adm/css/lib/sweetalert/sweetalert.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url() ?>assets/adm/css/helper.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/adm/css/style.css" rel="stylesheet">



    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- All Jquery -->
    <script src="<?= base_url() ?>assets/adm/js/lib/jquery/jquery.min.js"></script>
    <style type="text/css">

        /*MODAL*/
        .overlay {
            height: 100%;
            width: 100%;
            position: fixed;
            z-index: 2000 !important;
            top: 0;
            left: 0;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0, 0.5);
            display: table;
        }
        .container-dialog{
            vertical-align: middle;
            /*border: 1px solid red;*/

            display:table-cell;
            vertical-align:middle;
            /*text-align:center;*/
        }
        .dialog-center{
            /*border: 1px solid blue;*/
            margin:auto;
            
            padding: 9px;
            border-radius: 8px;
            background: #F2F2F2;
        }
        .title-dialog{
            text-align: left;
            border-bottom: 1px solid #AEAEAE;
            min-height: 40px;
            margin-bottom: 7px;
        }
        #errorMessage, #infoMessage{
            color: #F21E1E!important;
        }

        #content-dialog{
            max-height: 80vh;
            overflow-x: auto;
        }
        /*MODAL END*/


        /*PROGRESS UPLOAD*/
        #progress {
         width: 100%;   
         border: 0px solid black;
         position: relative;
         padding: 1px;
        }

        #percent {
         position: absolute;   
         left: 70%;
        }

        #bar {
         height: 16px;
         background-color: #00FF00;
         width: 0%;
        }
        /*PROGRESS UPLOAD END*/
    </style>
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>

    <div class="overlay" style="display: none; ">
        <div class="col-lg-12 col-xs-12 container-dialog">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 dialog-center">
                <div class="title-dialog ">
                    <span id="title-dialog"></span>
                    <button type="button" class="btn btn-danger btn-rounded btn-xs pull-right" onclick="close_dialog()">X</button>
                </div>
                <div  id="content-dialog" class="col-md-12 col-sm-12 col-xs-12">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <!-- header header  -->
        <div class="header">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <!-- Logo -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?= base_url() ?>">
                        <!-- Logo icon -->
                        <b><img src="<?= base_url() ?>assets/img/favicon-32x32.png" alt="homepage" class="dark-logo" /></b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>Panel</span>
                    </a>
                </div>
                <!-- End Logo -->
                <div class="navbar-collapse">
                    <!-- toggle and nav items -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted  " href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted  " href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- User profile and search -->
                    <ul class="navbar-nav my-lg-0">
                        <li class="nav-item hidden-sm-down search-box"> <a class="nav-link hidden-sm-down text-muted  " href="javascript:void(0)"><?= $this->session->userdata('identity') ?></a>
                           
                        </li>
                       
                        <!-- Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted  " href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="<?= base_url() ?>assets/adm/images/user.png" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right animated zoomIn">
                                <ul class="dropdown-user">
                                    <li><a href="#"><i class="ti-settings"></i> Setting</a></li>
                                    <li><a href="<?= base_url() ?>auth/logout"><i class="fa fa-power-off"></i> Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- End header header -->
        <!-- Left Sidebar  -->
        <div class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <?php
                            $this->load->library(array('ion_auth'));
                            $acl = $this->ion_auth->allowed_menu(); // panggil fungsi
                        ?>
                        <li class="nav-devider"></li>
                        <li class="nav-label">Home</li>
                        <li><a href="javascript:void(0)" aria-expanded="false" onclick="load_menu('<?= base_url() ?>jxpanel/banner')"><i class="fa fa-desktop"></i><span class="hide-menu">Banner</span></a></li>

                        <li class="nav-label">Other</li>
                        <?php if(array_key_exists('account', $acl)){  ?> 
                        
                        <li><a href="<?= base_url() ?>account" aria-expanded="false"><i class="fa fa-group"></i><span class="hide-menu">Account</span></a></li>

                        <?php  }?>

                        <!-- <li><a href="javascript:void(0)" aria-expanded="false" onclick="load_modal('auth/change_password')"><i class="fa fa-lock"></i><span class="hide-menu">Change Password</span></a></li> -->
                        <li><a href="javascript:void(0)" aria-expanded="false" onclick="load_modal('<?= base_url() ?>auth/change_password')"><i class="fa fa-lock"></i><span class="hide-menu">Change Password</span></a></li>
                        <li><a href="<?= base_url() ?>auth/logout" aria-expanded="false"><i class="fa fa-sign-out"></i><span class="hide-menu">Logout</span></a></li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </div>
        <!-- End Left Sidebar  -->
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div id="content" class="row page-titles">
                