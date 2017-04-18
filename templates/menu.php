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
        <li><a href="ipdatabase.php">IPs</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
        <?php
        if($user->is_loggedin()){
          $userid = $_SESSION["User"];
          if($user->hasRole($userid, "Admin")){
            echo "<li><a href='admin.php'><span class='glyphicon glyphicon-edit'></span> Admin</a></li>";
          }

          echo "<li class='dropdown'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'>" . $user->getName($userid) . "<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='usersettings.php'>Settings</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'><span class='glyphicon glyphicon-log-out'></span> Logout</a></li>
          </ul>
          </li>";
        } else {
          echo "<li><a href='login.php'>Login</a></li>";
          //echo "<li><a href='register.php'>Register</a></li>";
        }

        ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>