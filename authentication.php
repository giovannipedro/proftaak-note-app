<?php
include_once('php/security/session.php');
$secure = false;
include_once('php/security/secure.php');

if($_SESSION['loggedin']) { header("Location: /dashboard");}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Auth</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="description" content="All your tasks in 1 place"/>
    <meta name="theme-color" content="#FFFFFF"/>

    <link rel="apple-touch-icon" href="assets/img/icon/apple-touch-icon.png">
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="assets/js/main.js" defer></script>
  </head>
  <body>
    <div id="container">
        <div class="sub">
            <?php 
                $do = $_REQUEST['do'];
                switch($do) {
                    case 1:
                        echo '<form action="" method="POST" id="admin-login" class="register">
                        <div class="top">
                            <div class="names">
                                <input regex="name" name="voornaam" id="voornaam" type="text" placeholder="Voornaam" value=""/>
                                <input regex="name" name="achternaam" id="achternaam" type="text" placeholder="Achternaam" value=""/>
                            </div>
                            <input regex="username" name="username" id="username" type="text" placeholder="Gebruikersnaam" value=""/>
                            <input regex="dob" name="dob" id="dob" type="date" placeholder="" value=""/>
                        


                            <input regex="email" name="email" id="email" type="text" placeholder="email" value="'.$_COOKIE['PHPEMAIL'].'"/>
                            <input regex="password" name="password" id="password" type="password" placeholder="Wachtwoord" value=""/>
                            <input regex="password" name="repeat-password" id="repeat-password" type="password" placeholder="Herhaal Wachtwoord" value=""/>
                        </div>
                        <div class="bottom">
                            <button type="submit">Register</button>
                            <span id="form-wrong-message"></span>
                            <div>
                                <a href="/login">Heb je al een account?</a>
                                <a href="/login">Klik mij om je aan te melden</a>
                            </div>
                        </div>
                    </form>';
                        break;
                    case 2:
                        echo'<form action="" method="POST" id="admin-login" class="login">
                        <div class="top">
                            <input regex="username" name="username" id="username" type="text" placeholder="Gebruikersnaam" value="'.$_COOKIE['PHPUSERNAME'].'"/>
                            <input regex="none" name="password" id="password" type="password" placeholder="Wachtwoord" value="'.$_COOKIE['PHPPASSWORD'].'"/>
                
                            <label for="remember">
                                <input name="remember" type="checkbox" value="1"/>
                                Onthoud wachtwoord
                            </label>
                        </div>
        
                        <div class="bottom">
                            <button type="submit">Login</button>
                            <span id="form-wrong-message"></span>
                            <div>
                                <a href="/register">Heb je geen account?</a>
                                <a href="/register">Klik mij om er een te maken</a>
                            </div>
                        </div>
                    </form>';
                        break;
                }
            ?>
            </div>
        </div>
    </body>
</html>