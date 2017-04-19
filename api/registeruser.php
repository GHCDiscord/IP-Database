<?php
require_once __DIR__ . "/../dbconfig.php";

$error = false;

if(!isset($_POST["token"]) && !$user->verifyToken($_POST["token"])){
	$error = "wrong token";
}

if(isset($_POST["name"])){
	$name = $_POST["name"];
} else {
	$error = "no name";
}

if(isset($_POST["password"])){
	$password = $_POST["password"];
} else {
	$error = "no password";
}

// OPTIONAL
if(isset($_POST["email"])){
	$email = $_POST["email"];
} else {
	$email = "";
}

if(isset($_POST["discorduser"])){
	$discorduser = $_POST["discorduser"];
} else {
	$error = "no discord";
}

if($error == false){
	if(!$user->nameAvailable($name)){
		$error = "name taken";
	}
}

if($error == false){
	if(!$user->discordAvailable($discorduser)){
		$error = "discord taken";
	}
}



if($error == false){
	$expdate = $user->returnExpireDate();
	$user->register($name, $email, $password, $expdate);
	$editid = $user->findUserWithName($name);
	$user->setDiscord($editid, $discorduser);
	echo "success";
} else {
	echo $error;
}