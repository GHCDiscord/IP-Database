<?php
require_once __DIR__ . '/../dbconfig.php';

$email = $_GET['q'];

if($user->emailAvailable($email)){
    echo "true";
} else {
    echo "false";
}