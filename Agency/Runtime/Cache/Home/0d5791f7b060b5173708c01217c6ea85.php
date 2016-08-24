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
    $(document).ready(function () {
        if ('<?php echo ($vGet["phone"]); ?>' != '') {
            $("#phone").val('<?php echo ($vGet["phone"]); ?>');
        } else if ('<?php echo ($vGet["phone"]); ?>' != '') {
            $("#wechat").val('<?php echo ($vGet["wechat"]); ?>');
        } else if ('<?php echo ($vGet["wechat"]); ?>' != '') {
            $("#email").val('<?php echo ($vGet["email"]); ?>');
        } else if ('<?php echo ($vGet["email"]); ?>' != '') {
            $("#nickname").val('<?php echo ($vGet["nickname"]); ?>');
        }
    });
</script>

<div class="page-content" style="min-height:648px !important">

    <div class="container-fluid">

        <div class="portlet box yellow">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-search"></i><?php echo (L("$vTitle")); ?></div>
    </div>
    <div class="portlet-body">
        <form id="mainform" role="form" action="" method="get">
            <input type="hidden" name="s" value="<?php echo ($s); ?>">
            <div class="row">
                <div class="col-md-12">
                    <div class="input-group input">

        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_phone")); ?>" id="phone" name="phone">
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_wechat")); ?>" id="wechat" name="wechat">
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_nickname")); ?>" id="nickname" name="nickname">
        or
        <input type="text" class="form-control input-small" placeholder="<?php echo (L("user_email")); ?>" id="email" name="email">

        <button class="btn red" type="submit" style="float:right; "><i class="fa fa-search"></i>&nbsp;<?php echo (L("search")); ?></button>

                            </div>
                </div>
            </div>
        </form>
    </div>
</div>

        <div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption"><i class="fa fa-table"></i><?php echo (L("$vTitle")); ?></div>
        <div class="tools">

        <a href="/agency/index.php?s=/Home/User/add&ret=<?php echo ($vSelfUrl); ?>" style="color:whitesmoke;"><?php echo (L("user_add")); ?></a>
                    </div>
        </div>
        <div class="portlet-body">

        <table class="table table-striped table-bordered table-hover table-full-width">
            <thead>
            <tr>
                <th><?php echo (L("uid")); ?></th>
                <th><?php echo (L("user_phone")); ?></th>
                <th><?php echo (L("user_wechat")); ?></th>
                <th><?php echo (L("user_nickname")); ?></th>
                <th><?php echo (L("user_name")); ?></th>
                <th><?php echo (L("user_gender")); ?></th>
                <th><?php echo (L("user_last_purchase_time")); ?></th>
                <th colspan="4"><?php echo (L("operation")); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(empty($vList)): ?><tr>
                    <td align="center" colspan="100%"><?php echo (L("no_data")); ?></td>
                </tr>
                <?php else: ?>
                <?php if(is_array($vList)): foreach($vList as $key=>$value): ?><tr>
                        <td><?php echo ($value["uid"]); ?></td>
                        <td><?php echo ($value["phone"]); ?></td>
                        <td><?php echo ($value["wechat"]); ?></td>
                        <td><?php echo ($value["nickname"]); ?></td>
                        <td><?php echo ($value["lastname"]); ?> <?php echo ($value["firstname"]); ?></td>
                        <td><?php echo ($value["gender"]); ?></td>
                        <td><?php echo ($value["last_purchase_time"]); ?></td>
                        <td>
                            <a href="javascript:;" id="click_detail_<?php echo ($value["uid"]); ?>"><?php echo (L("detail")); ?></a>
                        </td>
                        <td>
                            <a href="/agency/index.php?s=/Home/User/edit&uid=<?php echo ($value["uid"]); ?>&ret=<?php echo ($vSelfUrl); ?>"><?php echo (L("edit")); ?></a>
                        </td>
                        <td>
                            <a href="/agency/index.php?s=/Home/User/order&uid=<?php echo ($value["uid"]); ?>&ret=<?php echo ($vSelfUrl); ?>"><?php echo (L("user_order")); ?></a>
                        </td>
                        <td>
                            <a href="/agency/index.php?s=/Home/Order&uid=<?php echo ($value["uid"]); ?>"><?php echo (L("order_module")); ?></a>
                        </td>
                    </tr>
                    <tr id="detail_<?php echo ($value["uid"]); ?>" style="display:none;">
                        <td class="details" colspan="100%">
                            <table class="table table-bordered table-hover table-full-width">
                                <tbody>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_ctime")); ?></td>
                                    <td><?php echo ($value["ctime"]); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_email")); ?></td>
                                    <td><?php echo ($value["email"]); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_total_purchase")); ?></td>
                                    <td><?php echo ($value["total_purchase"]); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_total_profit")); ?></td>
                                    <td><?php echo ($value["total_profit"]); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_total_order_count")); ?></td>
                                    <td><?php echo ($value["total_order_count"]); ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-2"><?php echo (L("user_profile")); ?></td>
                                    <td><?php echo ($value["profile"]); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <input type="hidden" id="switch_detail_<?php echo ($value["uid"]); ?>" value="0"/>
                    <input type="hidden" id="switch_create_<?php echo ($value["uid"]); ?>" value="0"/>
                    <script type="text/javascript">
                        $(document).ready(function () {

                            //详情
                            $("#click_detail_<?php echo ($value["uid"]); ?>").click(function () {
                                if ($("#switch_detail_<?php echo ($value["uid"]); ?>").val() == '0') {
                                    $("#click_detail_<?php echo ($value["uid"]); ?>").html('<?php echo (L("close")); ?>');
                                    $("#switch_detail_<?php echo ($value["uid"]); ?>").val('1');
                                    $("#detail_<?php echo ($value["uid"]); ?>").show('fast');
                                } else {
                                    $("#click_detail_<?php echo ($value["uid"]); ?>").html('<?php echo (L("detail")); ?>');
                                    $("#switch_detail_<?php echo ($value["uid"]); ?>").val('0');
                                    $("#detail_<?php echo ($value["uid"]); ?>").hide('fast');
                                }
                            });
                        });
                    </script><?php endforeach; endif; endif; ?>
            </tbody>
        </table>

        <?php echo ($vPageBar); ?>
            </div>
</div>

    </div>

</div>

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