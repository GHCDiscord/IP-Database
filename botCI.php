<?php
require_once "dbconfig.php";

if(!$user->is_loggedin()){
    $user->redirect("index.php");
}

if(!$user->hasRole($_SESSION["User"], "Admin")){
    $user->redirect("index.php");
}

if(isset($_GET["error"])){
    $error = true;
}

include "templates/header.php";
include "templates/navbar.php";

?>
<!-- files -->
<link href="css/botCI.css" type="text/css" rel="stylesheet" />
<script src="js/botCI.js" type="text/javascript"></script>

    <!-- body html -->
    <div class="container">

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select bot command <span class="caret" id="dropdownCaret"></span></button>
            <ul class="dropdown-menu columns" id="botcommandDropdown"></ul>
        </div>

        <div id="mirFaelltGeradeKeinNameEin">
            <div id="messageContainer"></div>
            <div id="jumbotronContainer"></div>
        </div>

    </div>

<?php 

$json = $_POST['botCommandJSON'];

if (json_decode($json) != null)
{
    $file = fopen('botCommands.json','w+');
    fwrite($file, $json);
    fclose($file);
}

include "templates/footer.php";
?>