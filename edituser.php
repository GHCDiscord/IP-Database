<?php

require_once "dbconfig.php";
$editid = $_GET["id"];
if(!$user->is_loggedin()){
    $user->redirect("login.php");
}

if(!$user->hasRole($_SESSION["User"], "Admin")){
    $user->redirect("index.php");
}

if(!isset($_GET["id"])){
    $user->redirect("admin.php");
}

if(isset($_POST["deleteuser"])){
    $user->delete($editid);
    $user->redirect("admin.php");
}

if(isset($_POST["edituser"])){
    $name = $_POST["name"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    echo "<script>console.log('$role')</script>";
    $user->setRole($editid, $role);
    $user->setName($editid, $name);
    $user->setEmail($editid, $email);
    $user->redirect("admin.php");

}





include "templates/header.php";
include "templates/menu.php";
?>

<div class="container">
    <form class="form-horizontal" action="edituser.php<?php echo "?id={$editid}" ?>" method="post">
        <div class="form-group">
            <label for="inputName" class="col-sm-2 control-label">Name</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" id="inputName" <?php echo 'value="' . $user->getData("Username", $editid) . '"'; ?> placeholder="Name">
            </div>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="col-sm-2 control-label">E-Mail</label>
            <div class="col-sm-10">
                <input type="email" name="email" class="form-control" id="inputEmail" <?php echo 'value="' . $user->getData("Email", $editid) . '"'; ?> placeholder="E-Mail">
            </div>
        </div>
        <div class="form-group">
            <label for="inputRolle" class="col-sm-2 control-label">Rolle</label>
            <div class="col-sm-10">
                <select name="role" class="form-control" id="inputRolle">
                    <option value="Admin">Admin</option>
                    <option value="Moderator">Moderator</option>
                    <option value="User">User</option>
                </select>
            </div>
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