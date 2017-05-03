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

if(isset($_POST["editpassword"])){
    $error == false;
    $success == false;
    $oldPassword = $_POST["oldPassword"];
    $newPassword = $_POST["newPassword"];
    $repeatNewPassword = $_POST["repeatNewPassword"];

    if(strlen($newPassword) == 0){
        $error = "Das neue Passwort kann nicht leer sein!";
    }

    if($user->loginDataCorrect($user->getData("Username", $_SESSION["User"]), $oldPassword)){
        if($repeatNewPassword == $newPassword){
            $user->setPassword($newPassword, $_SESSION["User"]);
            $success = "Passwort erfolgreich geändert!";
        } else {
            $error = "Das neue Passwort stimmt nicht überein!";
        }
    } else {
        $error = "Das alte Passwort ist falsch!";
    }
}
include "templates/header.php";
include "templates/navbar.php";
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
    

    <form class="form-horizontal" action="usersettings.php<?php echo "?id={$editid}" ?>" method="post">
        <div class="form-group">
            <label for="oldPasswordInput" class="col-sm-2 control-label">Altes Passwort</label>
            <div class="col-sm-10">
                <input type="password" name="oldPassword" class="form-control" id="oldPasswordInput" placeholder="Dein altes Passwort">
            </div>
        </div>
        <div class="form-group">
            <label for="newPasswordInput" class="col-sm-2 control-label">Neues Passwort</label>
            <div class="col-sm-10">
                <input type="password" name="newPassword" class="form-control" id="newPasswordInput" placeholder="Dein neues Passwort">
            </div>
        </div>
        <div class="form-group">
            <label for="repeatNewPasswordInput" class="col-sm-2 control-label">Neues Passwort wiederholen</label>
            <div class="col-sm-10">
                <input type="password" name="repeatNewPassword" class="form-control" id="repeatNewPasswordInput" placeholder="Wiederhole dein neues Passwort">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="editpassword" class="btn btn-success">Passwort ändern</button>
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

    if(isset($error) && $error != false){
        echo "<div style='margin-top: 50px;' class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <p>{$error}</p> 
    </div>";
    }
    if(isset($success) && $success != false){
        echo "<div style='margin-top: 50px;' class='alert alert-success alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <p>{$success}</p> 
    </div>";
    }
    ?>
</div>

<?php
include "templates/footer.php";
?>