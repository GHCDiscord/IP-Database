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
        <li id='ipdatabase.php' class=''><a href="ipdatabase.php"><i class='fa fa-laptop fa-lg' aria-hidden='true'></i>&nbsp; IPs</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
        <?php
        if($user->is_loggedin()){
          $userid = $_SESSION["User"];
          if($user->hasRole($userid, "Admin")){
            echo "<li id='admin.php' class=''><a href='admin.php'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i>&nbsp; Admin</a></li>";
          }
          
          /* Bei usersettings.php wird das mit der active class für das <li> tag nur funktionieren, wenn er auf usersettings.php ist.
          Das ist jetzt kein Problem, da wir nur diese eine Seite in dem Dropdown haben. Wenn wir später mal mehr haben muss ich das wahrscheinlich ändern! */ 
          echo 
          "<li class='dropdown' id='usersettings.php'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><i class='fa fa-user-circle-o fa-lg' aria-hidden='true'></i>&nbsp; ". $user->getName($userid) ." &nbsp;<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='usersettings.php'><i class='fa fa-wrench fa-fw' aria-hidden='true'></i>&nbsp; Settings</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'><i class='fa fa-sign-out fa-fw' aria-hidden='true'></i>&nbsp; Logout</a></li>
          </ul>
          </li>";
        } else {
          echo "<li id='login.php' class=''><a href='login.php'><i class='fa fa-sign-in fa-lg' aria-hidden='true'></i>&nbsp; Login</a></li>";
          //echo "<li><a href='register.php'>Register</a></li>";
        }

        ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>