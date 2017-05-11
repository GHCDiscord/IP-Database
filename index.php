<?php
//error_reporting(E_ALL);

require_once 'dbconfig.php';

if($user->is_loggedin())
{
    $user->redirect("ipdatabase.php");
} else {
    $user->redirect("login.php");
}

include "templates/header.php";
include "templates/navbar.php";

?>