<?

require_once 'dbconfig.php';

if($user->is_loggedin()){
    $user->redirect('index.php');
}
if(isset($_GET["login"])){
    $uname = $_POST['name'];
    $password = $_POST['password'];

    if($user->login($uname, $password)){
        $user->redirect('index.php');
    } else {
        $error = true;
    }
}

include 'templates/header.php';
include 'templates/menu.php';


?>

<div class="container">
    <div class="col-md-12">
    <?php
    // If the login has an error:
    if(isset($error)){
    ?>

    <div class='alert alert-danger alert-dismissible fade in' role='alert'> 
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span></button> 
        <h4>3RR0R!</h4> 
        <p>Nutzername und/oder Passwort inkorrekt!</p> 
    </div>

    <?php
    }
    ?>

        <form action="?login=1" method="post" class="">
            <div class="form-group">
                <label for="inputUsername">Username/E-Mail</label>
                <input type="text" name="name" id="inputUsername" class="form-control" placeholder="Username/E-Mail">
            </div>

            <div class="form-group">
                <label for="inputPassword">Password</label>
                <input type="password" name="password" class="form-control" id="inputPassword">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <label><p>Noch keinen Account? <a href="register.php">Hier regestrieren!</a></label>
    </div> <!-- Col Md 12 -->
</div> <!-- Container -->