<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Loan Project! | Login Page</title>

    <!-- Bootstrap -->
    <?= plugin_css('bootstrap/dist/css/bootstrap.min.css');?>
    <!-- Font Awesome -->
    <?= plugin_css('font-awesome/css/font-awesome.min.css');?>

    <!-- Custom Theme Style -->
    <?= css('custom.css');?>
  </head>

  <body style="background:#F7F7F7;">
    <div class="">
      <a class="hiddenanchor" id="toregister"></a>
      <a class="hiddenanchor" id="tologin"></a>

      <div id="wrapper">
        <div id="login" class=" form" data-ng-app="loginApp">
          <section class="login_content" data-ng-controller="loginController">
            <form ng-submit="LoginAttempt()" ng-init="method='post'; url='<?= base_url();?>'">
              <h1>Login Form</h1>
              <div class="alert alert-danger" ng-show="login_error">
                <ul class="list-unstyled">
                  <li data-ng-repeat="error_msg in errors">{{error_msg}}</li>
                </ul>
              </div>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" ng-model="username"/>
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" ng-model="password"/>
              </div>
              <div>
                <button type="submit" class="btn btn-default">Log in</button>
              </div>
              <div class="clearfix"></div>
              <div class="separator">

                <div class="clearfix"></div>
                <br />
                <div>
                  <h1><i class="fa fa-rub" style="font-size: 26px;"></i> Loan Project!</h1>

                  <p>©2015 All Rights Reserved. Loan Project. Privacy and Terms</p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>

    <!-- jQuery 2.1.4 -->
    <?= plugin_script('jquery/dist/jquery.min.js');?>
    <!-- Bootstrap 3.3.5 -->
    <?= plugin_script('bootstrap/dist/js/bootstrap.min.js');?>

    <?= script('angularjs.js');?>
    <?= script('login.js');?>
    <?= script('login_app.js');?>
  </body>
</html>