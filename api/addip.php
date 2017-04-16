<?php
require_once __DIR__ . "/../dbconfig.php";

if(!$user->is_loggedin()){
    $user->redirect(__DIR__ . "/../login.php");
}

echo $ip->add("","","","","","");
