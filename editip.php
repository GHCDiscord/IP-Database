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
    $clan = $_POST["clan"];
    $ip->setIP($editip, $editid);
    $ip->setAttackedName($name, $editid);
    $ip->setReputation($rep, $editid);
    $ip->setMiners($miners, $editid);
    $ip->setDescription($description, $editid);
    $ip->setClan($clan, $editid);
    $ip->setLastUpdated(date("Y-m-d"), $editid);
    $ip->clearReports($editid);
    $success = true;
    $user->redirect("ipdatabase.php?editsuccess=1");

}
include "templates/header.php";
include "templates/navbar.php";
?>
<script>
    document.getElementById('navIPs').classList.add("active");
</script>

<div class="container">
    <?php
    if(isset($success)){

        echo '<div class="alert alert-success" role="alert">
            <a href="#" class="alert-link">Daten erfolgreich bearbeitet!</a>
        </div>';
    }
    $repanzahl = $ip->reportCount($editid);
    ?>
    <p>Diese IP wurde <?php echo $repanzahl; ?> mal gemeldet!</p>
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
            <label for="inputClan" class="col-sm-2 control-label">Clan</label>
            <div class="col-sm-10">
                <input type="text" name="clan" class="form-control" id="inputClan" value="<?php echo $ip->getData("Clan", $editid); ?>" placeholder="[ABC]">
            </div>
        </div>
        <div class="form-group">
            <label for="inputDesc" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" rows="5" name="description" id="inputDesc" value="" placeholder="i = inaktiv"><?php echo $ip->getData("Description", $editid); ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="button" name="deleteuser" value="Delete" class="btn btn-danger">Delete</button>
                <button type="submit" name="edituser" class="btn btn-success" autofocus>Update</button>
            </div>
        </div>
    </form>
    <?php
    if(!$repanzahl == 0){
    echo "<h3>Diese IP wurde gemeldet von:</h3>";
    echo $ip->listReportNames($editid);}?>
</div>
<?php include "templates/footer.php";?>
