<?php

require_once "dbconfig.php";

if(isset($_SESSION['User'])){
    $user->redirect("index.php");
}

include "templates/header.php";
include "templates/menu.php";

if(isset($_GET['register'])){
    $error = false;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['repeatpassword'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        message_error("emailMessage", "emailDiv", "<p>Diese E-Mail ist nicht gültig!</p>");
        $error = true;
    }

    if(strlen($username) == 0) {
        message_error("usernameMessage", "usernameDiv", "<p>Bitte gib einen Nutzernamen an!</p>");
        $error = true;
    }

    if(strlen($password) == 0) {
        message_error("passwordMessage", "passwordDiv", "<p>Bitte gib ein Passwort an!</p>");
        $error = true;
    }

    if($password != $password2) {
        message_error("passwordMessage", "passwordDiv", "<p>Passwörter nicht gleich!</p>");
        $error = true;
    }

    if(!$error){
        $usernameAvailable = $user->nameAvailable($username);
        if(!$usernameAvailable){
            message_error("usernameMessage", "usernameDiv", "<p>Nutzername schon vergeben!</p>");
            $error = true;
        }
    }

    if(!$error){
        $emailAvailable = $user->emailAvailable($email);
        if(!$emailAvailable){
            message_error("emailMessage", "emailDiv", "<p>E-Mail schon vergeben!</p>");
            $error = true;            
        }
    }

    if(!$error){
        $success = $user->register($username, $email, $password);

        if($success){
            $user->redirect("index.php");
        }
    }
}

function message_error($id, $idDiv, $message){
    echo "
    <script>
        $( document ).ready(function() {
            document.getElementById('$id').innerHTML = '$message';
            document.getElementById('$idDiv').className = 'form-group has-error';
        });

    </script>
    ";
}
?>

<div class="container">
    <form action="?register=1" method="post">
    <div class="form-group "  id="usernameDiv">
        <label for="inputUsername">Username</label>
        <input type="text" class="form-control" id="inputUsername" placeholder="TheLegend27" name="username" onchange="checkIfNameTaken(this.value)">
        <span id="usernameMessage" class="help-block"></span>
        <!-- HEY LOOK ITS AJAX -->
        <script>
        function checkIfNameTaken(str) {
            if(str == ""){
                docoument.getElementById("usernameMessage").innerHTML = "";
                return;
            } else {
                if(window.XMLHttpRequest){
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                  if(this.readyState == 4 && this.status == 200){
                    if(this.responseText == "true"){
                        document.getElementById("usernameMessage").innerHTML = "<p class='has-success'>Dieser Name ist verfügbar!</p>";
                        document.getElementById("usernameDiv").className = "form-group has-success";
                        //$('registerButton').prop('disabled', false);
                    } else {
                        document.getElementById("usernameMessage").innerHTML = "<p class='has-error'>Dieser Name ist nicht verfügbar!</p>";
                        document.getElementById("usernameDiv").className = "form-group has-error";
                        //$('registerButton').prop('disabled', true);
                    }

                  }
                };
                xmlhttp.open("GET","api/nameavailable.php?q="+str,true);
                xmlhttp.send();
            }

        }
        </script>
    </div>
        <div class="form-group" id="emailDiv">
        <label for="inputEmail">E-Mail</label>
        <input type="email" class="form-control" id="inputEmail" placeholder="max@mustermann.de" name="email" onchange="checkIfEmailTaken(this.value)">
        <span id="emailMessage" class="help-block"></span>
        <!-- WELL WELL WELL ITS AJAX AGAIN -->
        <script>
        function checkIfEmailTaken(str) {
            if(str == ""){
                docoument.getElementById("emailMessage").innerHTML = "";
                return;
            } else {
                if(window.XMLHttpRequest){
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                  if(this.readyState == 4 && this.status == 200){
                    if(this.responseText == "true"){
                        document.getElementById("emailMessage").innerHTML = "<p class='has-success'>Diese E-Mail ist verfügbar!</p>";
                        document.getElementById("emailDiv").className = "form-group has-success";
                        //$('registerButton').prop('disabled', false);
                    } else if (this.responseText == "false") {
                        document.getElementById("emailMessage").innerHTML = "<p class='has-error'>Diese E-Mail ist nicht verfübar!</p>";
                        document.getElementById("emailDiv").className = "form-group has-error";
                        //$('registerButton').prop('disabled', true);
                    }

                  }
                };
                xmlhttp.open("GET","api/emailavailable.php?q="+str,true);
                xmlhttp.send();
            }

        }
        </script>
    </div>
    <div class="form-group" id="passwordDiv">
        <label for="InputPassword">Passwort</label>
        <input type="password" class="form-control" id="InputPassword" placeholder="Password" name="password">
        <span class="help-block" id="passwordMessage"><strong>Hinweis: Wir übernehemen keine Haftung falls der Account gehackt wird, wenn ein schwaches Passwort gewählt wurde. Wir empfehlen mind. 8 Zeichen, Groß- und Kleinbuchstaben und Zeichen (@, #, * usw.).</span>
    </div>
    <div class="form-group">
        <label for="repeatInputPassword">Passwort wiederholen</label>
        <input type="password" class="form-control" id="repeatInputPassword" placeholder="Repeat Password" name="repeatpassword">
        <!-- WAIT?! NO AJAX AGAIN?! WHAT A SAD LIFE WE LIVE IN -->
    </div>
    <button type="submit" class="btn btn-success" id="registerButton">Regestrieren</button>
    </form>
</div>

