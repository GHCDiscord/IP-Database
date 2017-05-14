<nav class="navbar navbar-default navbar-static-top navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand"  href="index.php" id="navbarBrandText"></a>
      <script>
        if ($(window).width() < 400) {
        try {
            document.getElementById("navbarBrandText").innerHTML = '<img src="images/icon.svg" width="30" height="30" style="margin-right: 10px; display: inline-block!important; vertical-align: top!important" />GHC';
        } catch (e) {
            console.log("Nicht wichtiger Fehler!: " + e);
        }
    } else {
        try {
            document.getElementById("navbarBrandText").innerHTML = '<img src="images/icon.svg" width="30" height="30" style="margin-right: 10px; display: inline-block!important; vertical-align: top!important" />German Hacker Community';
        } catch (e) {
            console.log("Nicht wichtiger Fehler!: " + e);
        }
    }
      </script>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <!--<li><a href="index.php">Home</a></li>-->
        <li id='ipdatabase.php?fav=0' class=''><a href="ipdatabase.php?fav=0"><i class='fa fa-laptop fa-lg' aria-hidden='true'></i>&nbsp; IPs</a></li>
        <li id='ipdatabase.php?fav=1' class=''><a href="ipdatabase.php?fav=1"><i class='fa fa-star fa-lg' aria-hidden='true'></i>&nbsp; Favouriten</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        
        <?php
        if($user->is_loggedin()){
          
          if($_SESSION['Role'] == "Admin"){
            //echo "<li id='botCI.php' class=''><a href='botCI.php'><i class='fa fa-commenting-o fa-lg' aria-hidden='true'></i>&nbsp; BotCI</a></li>";
            echo "<li id='admin.php' class=''><a href='admin.php'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i>&nbsp; Admin</a></li>";
          }
          if($_SESSION['Role'] == "Admin" || $_SESSION['Role'] == "Moderator"){
            
            echo "<li id='reportedips.php' class=''><a href='reportedips.php'><i class='fa fa-pencil-square-o fa-lg' aria-hidden='true'></i>&nbsp; ReportedIP's</a></li>";
          }
          /* Bei usersettings.php wird das mit der active class für das <li> tag nur funktionieren, wenn er auf usersettings.php ist.
          Das ist jetzt kein Problem, da wir nur diese eine Seite in dem Dropdown haben. Wenn wir später mal mehr haben muss ich das wahrscheinlich ändern! */ 
          echo 
          "<li class='dropdown' id='usersettings.php'>
          <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><i class='fa fa-user-circle-o fa-lg' aria-hidden='true'></i>&nbsp; ". $user->getName($_SESSION['User']) ." &nbsp;<span class='caret'></span></a>
          <ul class='dropdown-menu'>
            <li><a href='usersettings.php'><i class='fa fa-wrench fa-fw' aria-hidden='true'></i>&nbsp; Settings</a></li>
            <li role='separator' class='divider'></li>
            <li><a href='logout.php'><i class='fa fa-sign-out fa-fw' aria-hidden='true'></i>&nbsp; Logout</a></li>
          </ul>
          </li>";
        } else {
          echo "<li id='login.php' class=''><a href='login.php'><i class='fa fa-sign-in fa-lg' aria-hidden='true'></i>&nbsp; Login</a></li>";
        }

        ?>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>