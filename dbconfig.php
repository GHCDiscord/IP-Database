<?php
session_start();

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "ghc";
$charset = "utf8";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name};charset={$charset}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "dbconfig.php: Connection to database failed: " . $e->getMessage();
}


include_once 'classes/user.php';
include_once 'classes/ip.php';
include_once 'classes/paginator.php';
$user = new USER($DB_con);
$ip = new IP($DB_con);

if($user->is_loggedin()){
	if($user->isExpired($_SESSION["User"])){
		$user->logout();
	}
}