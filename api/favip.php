<?php
require_once __DIR__ . "/../dbconfig.php";

if(!$user->is_loggedin()){
	$user->redirect("../ipdatabase.php");
}

$id = $_GET["id"];
if($user->hasFav($_SESSION["User"], $id)){
	echo "false";
} else {
	if($ip->fav($id, $_SESSION["User"])){
		echo "true";
	} else {
		echo "false";
	}
}

