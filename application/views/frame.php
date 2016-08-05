<!DOCTYPE html>
<html lang="en" ng-app="app">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title ng-controller="TitleCtrl">Loan Project! | {{tab_title}}</title>

    <!-- Bootstrap -->
    <?= plugin_css('bootstrap/dist/css/bootstrap.min.css');?>
    <!-- Font Awesome -->
    <?= plugin_css('font-awesome/css/font-awesome.min.css');?>
    <!-- bootstrap-progressbar -->
    <?= plugin_css('bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css');?>

    <!-- Custom Theme Style -->
    <?= css('custom.css');?>

    <?= css('xeditable.min.css');?>

    <base href="<?= base_url();?>" />
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="index.html" class="site_title"><i class="fa fa-rub" style="border-radius: 0% !important; border: 0px !important;"></i> <span>Loan Project!</span></a>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile">
              <div class="profile_pic">
                <?= images('user.png', 'navbar_picture');?>
              </div>
              <div class="profile_info">
                <span>Welcome,</span>
                <h2><?= user_name();?></h2>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <br />

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                  <li class="active"><a href="home"><i class="fa fa-home"></i> Home</a></li>
                  <li><a href="customers"><i class="fa fa-group"></i> Customers</a></li>
                  <li><a><i class="fa fa-money"></i> Loans <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="loans">View Loans</a></li>
                      <li><a href="loan_transactions"> View Loan Transactions</a></li>
                    </ul>
                  </li>
                  <li><a><i class="fa fa-bank"></i> Banks <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                      <li><a href="banks">View Banks</a></li>
                      <li><a href="bank_transactions"> View Bank Transactions</a></li>
                    </ul>
                  </li>
                </ul>
              </div>

            </div>
            <!-- /sidebar menu -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav class="" role="navigation">
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <?= images('user.png', 'account_picture');?><?= user_name();?>
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Change password</a></li>
                    <li><a href="#" ng-controller="TitleCtrl" ng-click="LogoutUser()"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>

        </div>
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main" style="min-height: 615px;">
          <div class="col-md-12 col-sm-12 col-xs-12" ng-view>

          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Loan Project by Nicole Rey Arriesga
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <?= plugin_script('jquery/dist/jquery.min.js');?>
    <!-- Bootstrap -->
    <?= plugin_script('bootstrap/dist/js/bootstrap.min.js');?>
    <!-- Plugins -->
    <?= plugin_script('bootstrap-datepicker/js/bootstrap-datepicker.min.js');?>
    <?= plugin_script('x_editable/bootstrap3-editable/js/bootstrap-editable.min.js');?>
    <?= plugin_script('bootstrap-datetimepicker-smalot/js/bootstrap-datetimepicker.min.js');?>
    <?= plugin_script('moment.js');?>
    <?= plugin_script('price-format.js');?>

    <!-- Custom Theme Scripts -->
    <?= script('custom.js');?>
    <?= script('angularjs.js');?>
    <?= script('angular-route.js');?>
    <?= script('xeditable.min.js');?>
    <?= script('app.js');?>
    <?= script('ui-bootstrap-tpls-1.3.2.min.js');?>
    <?= script('controllers/frame_controller.js');?>
    <?= script('controllers/customer_controller.js');?>
    <?= script('controllers/loan_controller.js');?>
    <?= script('controllers/loan_transaction_controller.js');?>
    <?= script('controllers/bank_controller.js');?>
    <?= script('controllers/bank_transaction_controller.js');?>

  </body>
</html>