<?php
require_once 'dbconfig.php';

if($user->is_loggedin())
{
    $user->redirect("ipdatabase.php");
} else {
    $user->redirect("login.php");
}

include "templates/header.php";
include "templates/menu.php";

?>