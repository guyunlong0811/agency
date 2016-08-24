<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<!--
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.0.2
Version: 1.5.4
Author: KeenThemes
Website: http://www.keenthemes.com/
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title><?php echo (L("title")); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="MobileOptimized" content="320">
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/agency/Public/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN THEME STYLES -->
    <link href="/agency/Public/css/style-metronic.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/css/pages/tasks.css" rel="stylesheet" type="text/css"/>
    <link href="/agency/Public/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color"/>
    <link href="/agency/Public/css/custom.css" rel="stylesheet" type="text/css"/>

    <!-- BEGIN DATE ONLINE COUNT STYLES -->

    <!-- END DATE ONLINE COUNT STYLES -->
    <link rel="stylesheet" type="text/css" href="/agency/Public/plugins/bootstrap-datepicker/css/datepicker.css" />
    <!-- END THEME STYLES -->
    <!--<link rel="shortcut icon" href="favicon.ico" />-->
    <link rel="shortcut icon" href="/agency/Public/img/logo.png" />

    <script src="/agency/Public/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse navbar-fixed-top">
<!-- BEGIN TOP NAVIGATION BAR -->
<div class="header-inner">
<!-- BEGIN LOGO -->
<a class="navbar-brand" href="/agency/index.php?s=">
    <?php echo (L("title")); ?>
    <!--<img src="/agency/Public/img/top.jpg" width="160" style="margin-top:-11px;" alt="logo" class="img-responsive" />-->
</a>
<p style="float: left; color: #FFFFFF; line-height: 40px; font-size: 22px; font-weight: bold;"><?php echo ($vApp); ?></p>
<!-- END LOGO -->
<!-- BEGIN RESPONSIVE MENU TOGGLER -->
<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
    <img src="/agency/Public/img/menu-toggler.png" alt="" />
</a>
<!-- END RESPONSIVE MENU TOGGLER -->
<!-- BEGIN TOP NAVIGATION MENU -->
<ul class="nav navbar-nav pull-right">

<!-- BEGIN USER LOGIN DROPDOWN -->
<li class="dropdown user">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <img alt="" src="/agency/Public/img/avatar.png" width="29" height="29" />
        <span class="username"><?php echo ($vNickname); ?></span>
        <i class="icon-angle-down"></i>
    </a>
    <ul class="dropdown-menu">
        <li><a href="/agency/index.php?s=/Home/Home/logout"><i class="fa fa-sign-out"></i> 登出</a></li>
    </ul>
</li>
<!-- END USER LOGIN DROPDOWN -->
</ul>
<!-- END TOP NAVIGATION MENU -->
</div>
<!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->
<div class="clearfix"></div>
<!-- BEGIN CONTAINER -->
<div class="page-container">

<!-- BEGIN SIDEBAR -->
<div class="page-sidebar navbar-collapse collapse">

    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu">

        <li>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
            <div class="sidebar-toggler hidden-phone"></div>
            <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        </li>

        <li>

        </li>

        <li class="start <?php if(($vIcon) == "home"): ?>active<?php endif; ?>">
            <a href="/agency/index.php?s=/Home">
                <i class="fa fa-home"></i>
                <span class="title">主页</span>
                <span class="selected"></span>
            </a>
        </li>

        <?php if(is_array($vFunction)): foreach($vFunction as $key=>$value): if(($vIcon) == $value["icon"]): ?><li class="selected">
            <li class="active open">
        <?php else: ?>
            <li ><?php endif; ?>

            <a href="javascript:;">
                <i class="fa fa-<?php echo ($value["icon"]); ?>"></i>
                <span class="title"><?php echo ($value["name"]); ?></span>
                <span class="selected"></span>
                <?php if(($vIcon) == $value["icon"]): ?><span class="arrow open"></span>
                <?php else: ?>
                    <span class="arrow"></span><?php endif; ?>
            </a>

            <ul class="sub-menu">
                <?php if(is_array($value["list"])): foreach($value["list"] as $key=>$val): if(($val["display"]) != "false"): if(($vController) == $val["controller"]): ?><li class="active">
                        <?php else: ?>
                            <li><?php endif; ?>
                        <a href="/agency/index.php?s=/Home/<?php echo ($val["controller"]); ?>"><?php echo ($val["name"]); ?></a>
                        </li><?php endif; endforeach; endif; ?>
            </ul>

        </li><?php endforeach; endif; ?>

    </ul>
    <!-- END SIDEBAR MENU -->
</div>
<!-- END SIDEBAR-->

<script type="text/javascript">
$(document).ready(function(){
    $("#username").val('');
    $("#nickname").val('');
    $("#password").val('');
    $("#repassword").val('');
    $("#ip_limit").val('');


	$("#mainform").submit(function(){
		
		if($("#username").val() == ''){
			alert('<?php echo (L("username_require")); ?>');
			return false;
		}else if($("#username").val().length < 4){
			alert('<?php echo (L("username_length")); ?>');
			return false;
		}else if($("#password").val() == ''){
			alert('<?php echo (L("password_require")); ?>');
			return false;
		}else if($("#password").val().length < 6){
			alert('<?php echo (L("password_length")); ?>');
			return false;
		}else if($("#repassword").val() == ''){
			alert('<?php echo (L("repassword_error")); ?>');
			return false;
		}else if($("#password").val() != $("#repassword").val()){
			alert('<?php echo (L("repassword_error")); ?>');
			return false;
		}else if($("#nickname").val() == ''){
			alert('<?php echo (L("nickname_require")); ?>');
			return false;
		}else{
			return true;
		}
		
	})
})
</script>

<!-- BEGIN PAGE -->
<!-- BEGIN PAGE -->
<div class="page-content" style="min-height:648px !important">

    <!-- BEGIN PAGE CONTAINER-->
    <div class="container-fluid">

        <!-- BEGIN PAGE HEADER-->
        <div class="row-fluid">
            <div class="span12">
                <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                <!--<h3 class="page-title">-->
                <!--Advanced Tables <small>advanced datatables</small>-->
                <!--</h3>-->
                <!-- END PAGE TITLE & BREADCRUMB-->
            </div>
        </div>
        <!-- END PAGE HEADER-->

        <!-- BEGIN PAGE CONTENT-->

        <div class="row-fluid">


            <div class="span12">

                <!-- BEGIN SAMPLE FORM PORTLET-->

                <div class="portlet box blue">

                    <div class="portlet-title">

                        <div class="caption"><i class="fa fa-plus"></i><?php echo (L("admin_add")); ?></div>

                    </div>

                    <div class="portlet-body form">

                        <!-- BEGIN FORM-->

                        <form action="" method="POST" id="mainform" class="form-horizontal">

                            <div class="form-body">

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo (L("admin_account")); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="username" name="username" >
                                        <span class="help-block"><?php echo (L("username_require")); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo (L("admin_nickname")); ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" id="nickname" name="nickname" >
                                        <span class="help-block"><?php echo (L("nickname_require")); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo (L("admin_pwd")); ?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="password" name="password" >
                                        <span class="help-block"><?php echo (L("pwd_input")); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo (L("admin_repwd")); ?></label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" id="repassword" name="repassword" >
                                        <span class="help-block"><?php echo (L("pwd_confirm")); ?></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo (L("ip_limit")); ?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="3" id="ip_limit" name="ip_limit"></textarea>
                                        <span class="help-block"><?php echo (L("ip_limit_input")); ?></span>
                                    </div>
                                </div>

                            </div>

                            <div class="form-actions">

                                <button type="submit" class="btn blue"><i class="fa fa-plus"></i>&nbsp;<?php echo (L("add")); ?></button>

                                <button type="button" class="btn" onclick="javascript:window.location.href='/agency/index.php?s=/Home/Admin&p=<?php echo ($pg); ?>';"><i class="fa fa-undo"></i>&nbsp;<?php echo (L("return")); ?></button>

                            </div>

                        </form>

                        <!-- END FORM-->

                    </div>

                </div>

                <!-- END SAMPLE FORM PORTLET-->

            </div>
            <!-- END PAGE CONTENT-->

        </div>
        <!-- END PAGE CONTAINER-->

    </div>
    <!-- END PAGE -->

    </div>
<!-- END CONTAINER -->
<!-- BEGIN FOOTER -->
<div class="footer">
    <div class="footer-inner" style="margin-left:40%;">
        2014 &copy; Forever Game Network Technology Co., Ltd.
    </div>
    <div class="footer-tools">
			<span class="go-top">
			<i class="fa fa-angle-up"></i>
			</span>
    </div>
</div>
<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="/agency/Public/plugins/respond.min.js"></script>
<script src="/agency/Public/plugins/excanvas.min.js"></script>
<![endif]-->


<script src="/agency/Public/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
<script src="/agency/Public/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript" ></script>
<script src="/agency/Public/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/jquery.cookie.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/uniform/jquery.uniform.min.js" type="text/javascript" ></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN DATA ONLINE COUNT PLUGINS -->
<script src="/agency/Public/plugins/flot/jquery.flot.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/flot/jquery.flot.resize.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/bootstrap-daterangepicker/moment.min.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/bootstrap-daterangepicker/daterangepicker.js" type="text/javascript"></script>
<script src="/agency/Public/plugins/gritter/js/jquery.gritter.js" type="text/javascript"></script>
<!-- END DATA ONLINE COUNT  PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/agency/Public/scripts/app.js" type="text/javascript"></script>
<script src="/agency/Public/scripts/index.js" type="text/javascript"></script>
<script src="/agency/Public/scripts/tasks.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script type="text/javascript" src="/agency/Public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

<script>
    jQuery(document).ready(function() {
        App.init(); // initlayout and core plugins
    });

    if('<?php echo ($vAlert); ?>' != ''){
        alert('<?php echo (L("$vAlert")); ?>');
    }
</script>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>