<?php
require_once "dbconfig.php";
$editid = $_GET["id"];

if(!$user->is_loggedin()){
    $user->redirect("login.php");
}

if(!$user->hasRole($_SESSION["User"], "Admin") && !$user->hasRole($_SESSION["User"], "Moderator")){
    $user->redirect("index.php");
}

if(!isset($_GET["id"])){
    $user->redirect("ipdatabase.php");
}

if(isset($_POST["deleteuser"])){
    $ip->remove($editid);
    $user->redirect("ipdatabase.php");
}

if(isset($_POST["edituser"])){
    $editip = $_POST["ip"];
    $name = $_POST["attackedUsername"];
    $rep = $_POST["reputation"];
    $miners = $_POST["miners"];
    $description= $_POST["description"];
    $ip->setIP($editip, $editid);
    $ip->setAttackedName($name, $editid);
    $ip->setReputation($rep, $editid);
    $ip->setMiners($miners, $editid);
    $ip->setDescription($description, $editid);
    $ip->setLastUpdated(date("Y-m-d"), $editid);
    $user->redirect("ipdatabase.php");

}

include "templates/header.php";
include "templates/menu.php";
?>
<div class="container">
    <form class="form-horizontal" action="editip.php<?php echo "?id={$editid}" ?>" method="post">
        
        <div class="form-group">
            <label for="inputIP" class="col-sm-2 control-label">IP</label>
            <div class="col-sm-10">
                <input type="text" name="ip" class="form-control" id="inputIP" <?php echo 'value="' . $ip->getData("IP", $editid) . '"'; ?> placeholder="123.123.123.123">
            </div>
        </div>
        <div class="form-group">
            <label for="inputAttacked" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" name="attackedUsername" class="form-control" id="inputAttacked" value="<?php echo $ip->getData("Name", $editid); ?>" placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputRep" class="col-sm-2 control-label">Reputation</label>
            <div class="col-sm-10">
                <input type="number" name="reputation" class="form-control" id="inputRep" value="<?php echo $ip->getData("Reputation", $editid); ?>" placeholder="0">
            </div>
        </div>
        <div class="form-group">
            <label for="inputMiners" class="col-sm-2 control-label">Miners</label>
            <div class="col-sm-10">
                <input type="number" name="miners" class="form-control" id="inputMiners" value="<?php echo $ip->getData("Miners", $editid); ?>" placeholder="0">
            </div>
        </div>
        <div class="form-group">
            <label for="inputDesc" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" rows="5" name="description" id="inputDesc" value="<?php echo $ip->getData("Description", $editid); ?>" placeholder="i = inaktiv"></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="edituser" class="btn btn-success">Update</button>
                <button type="submit" name="deleteuser" value="Delete" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </form>
</div>
