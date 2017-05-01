<?
require_once 'dbconfig.php';

if($user->is_loggedin()){
    $user->redirect('index.php');
}
if(isset($_GET["login"])){
    $uname = $_POST['name'];
    $password = $_POST['password'];

    if($user->loginDataCorrect($uname, $password)){


        $userid = $user->findUserWithName($uname);        
        if($user->isExpired($userid)){
            $accountexpired = true;


        } else {
            if($user->login($uname, $password)){
                $user->redirect("ipdatabase.php");
            } else {
                $error = true;
            }
        }
    } else {
        $error = true;
    }
}

include 'templates/header.php';
?>
<nav class="navbar navbar-default navbar-static-top navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">GHC - German Hackers Community</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li><a href="index.php">Home</a></li>-->
        <li><a href="ipdatabase.php"><i class='fa fa-laptop fa-lg' aria-hidden='true'></i>&nbsp; IPs</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
        <?php
        if($user->is_loggedin()){
          $userid = $_SESSION["User"];
          if($user->hasRole($userid, "Admin")){
            echo "<li><a href='admin.php'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i>&nbsp; Admin</a></li>";
          }

          echo 
          "<li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><i class='fa fa-user-circle-o fa-lg' aria-hidden='true'></i>&nbsp; ". $user->getName($userid) ." &nbsp;<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='usersettings.php'><i class='fa fa-wrench fa-fw' aria-hidden='true'></i>&nbsp; Settings</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'><i class='fa fa-sign-out fa-fw' aria-hidden='true'></i>&nbsp; Logout</a></li>
          </ul>
          </li>";
        } else {
          echo "<li class='active'><a href='login.php'><i class='fa fa-sign-in fa-lg' aria-hidden='true'></i>&nbsp; Login</a></li>";
          //echo "<li><a href='register.php'>Register</a></li>";
        }

        ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
    <div class="col-md-12">
    <?php
    // If the login has an error:
    if($error){
    ?>

    <div class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <h4>3RR0R!</h4> 
        <p>Nutzername und/oder Passwort inkorrekt!</p> 
    </div>

    <?php
    }
    ?>

    <?php
    // If the login has an error:
    if($accountexpired == true && $user->loginDataCorrect($uname, $password)){
    ?>

    <div class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <h4>3RR0R!</h4> 
        <p>Dieser Account ist abgelaufen! Du kannst ihn aber auf unserem Discord wieder reaktivieren! Keine Angst, deine Accountdaten sind noch da!<br>Sollte jedoch deine IP in unserer Datenbank sein, könnte diese nun vielleicht angezeigt werden!</p> 
    </div>

    <?php
    }
    ?>

        <form action="?login=1" method="post" class="">
            <div class="form-group">
                <label for="inputUsername">Username/E-Mail</label>
                <input type="text" name="name" id="inputUsername" class="form-control" placeholder="Username/E-Mail">
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" class="form-control" id="inputPassword">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <label><p>Noch keinen Account? Regestriere dich auf unserem Discordserver mit unserem Bot!</label>
    </div> <!-- Col Md 12 -->
</div> <!-- Container -->

<?php
include 'templates/footer.php';