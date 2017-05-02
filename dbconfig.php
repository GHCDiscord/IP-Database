<?php
session_start();

$DB_host = "localhost";
$DB_user = "d0261fc2";
$DB_pass = "da1en#ban8";
$DB_name = "d0261fc2";
$charset = "utf8";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name};charset={$charset}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{

}


include_once 'classes/user.php';
include_once 'classes/ip.php';
$user = new USER($DB_con);
$ip = new IP($DB_con);

if($user->is_loggedin()){
	if($user->isExpired($_SESSION["User"])){
		$user->logout();
	}
}