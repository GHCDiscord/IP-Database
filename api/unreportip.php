<?php
require_once __DIR__ . "/../dbconfig.php";

if(!$user->is_loggedin()){
	$user->redirect("../ipdatabase.php");
}

$id = $_GET["id"];
if($user->hasReported($_SESSION["User"], $id)){
	if($ip->unreport($id, $_SESSION["User"])){
		echo "true";
	} else {
		echo "false";
	}
} else {
	echo "false";
}

