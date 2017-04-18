<?php
require_once  "dbconfig.php";

if(!$user->is_loggedin()){
	$user->redirect("login.php");
}

$editid = $_SESSION["User"];

if(isset($_POST["newToken"])){
	$user->generateToken($editid);
}

if(isset($_POST["edituser"])){
	$rep = $_POST["rep"];

	$user->setReputation($editid, $rep);
	$user->redirect("ipdatabase.php?editsuccess=1");
}

include "templates/header.php";
include "templates/menu.php";
?>


<div class="container">
    <form class="form-horizontal" action="usersettings.php<?php echo "?id={$editid}" ?>" method="post">
        <div class="form-group">
            <label for="inputRep" class="col-sm-2 control-label">Reputation</label>
            <div class="col-sm-10">
                <input type="number" name="rep" class="form-control" id="inputRep" <?php echo 'value="' . $user->getData("Reputation", $editid) . '"'; ?> placeholder="0">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="edituser" class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
    <?php 
    if($user->hasRole($_SESSION["User"], "Admin")){
    ?>
    <form class="form-horizontal" action="usersettings.php<?php echo "?id={$editid}" ?>" method="post">
    	<div class="form-group">
    		<label for="inputToken" class="col-sm-2 control-label">Token</label>
    		<div class="col-sm-8">
    		<input type="text" class="form-control" id="inputToken" disabled value="<?php echo $user->getToken($editid); ?>" >
    		</div>
    		<button type="submit" name="newToken" value="newToken" class="btn btn-info col-sm-2">Neuer Token</button>
    	</div>
    </form>
    <?php
    }
    ?>
</div>

<?php
include "templates/footer.php";
