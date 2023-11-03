<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

include_once("dbClass.php");
include_once("../security/functions.php");


(isset($_REQUEST['do'])) ? ($do = $_REQUEST['do']) : ($do = 0);

switch($do) {
    case 0:
        die;
        break;
    case '1':
        if($_POST['username'] && $_POST['password']) {
            $usr = $_POST['username'];
            $geg = $db->Req("SELECT id, password FROM `users` WHERE `username` = ? LIMIT 1", array($usr))->fetch();
            if(!empty($geg)) {
                $password = password_verify($_POST['password'], $geg['password']);
                if($password === true) {
                    if(isset($_POST['remember'])) {
                        setcookie("PHPUSERNAME", $_POST['username'], time() + (86400 * 30), "/");
                        setcookie("PHPPASSWORD", $_POST['password'], time() + (86400 * 30), "/");
                    }
                    $_SESSION['id'] = $geg['id'];
                    $_SESSION['loggedin'] = true;
                    echo json_encode(array('success' => 1));
                    break;
                }
            }
        }
        echo json_encode(array('success' => 0));
        break;
    case '2':
        if(

        regexCheck("email", $_POST['email']) && 
        regexCheck("password", $_POST['password']) && 
        regexCheck("name", $_POST['voornaam']) && 
        regexCheck("name", $_POST['achternaam']) &&  
        regexCheck("username", $_POST['username']) && 
        regexCheck("dob", $_POST['dob']) && 
        regexCheck("email", $_POST['email'])
        ) 

        {
            if($_POST['password'] == $_POST['repeat-password']) {
                

                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $voornaam = $_POST['voornaam'];
                $achternaam = $_POST['achternaam'];
                $usr = $_POST['username'];
                $dob = $_POST['dob'];
                
                $geg = $db->Req("SELECT 
                CASE 
                  WHEN COUNT(*) = 0 THEN 'none' 
                  WHEN COUNT(*) = 1 AND EXISTS(SELECT 1 FROM users WHERE email = ? AND username = ?) THEN 'both'
                  WHEN COUNT(*) = 1 AND EXISTS(SELECT 1 FROM users WHERE email = ?) THEN 'email'
                  WHEN COUNT(*) = 1 AND EXISTS(SELECT 1 FROM users WHERE username = ?) THEN 'username'
                  ELSE 'multiple'
                END AS result 
              FROM users 
              WHERE email = ? OR username = ?", array($email, $usr, $email, $usr, $email, $usr))->fetch();
                if($geg['result'] == "none") {
                    $geg = $db->Req("INSERT INTO `users` (`id`, `email`,`password`,`voornaam`, `achternaam`, `username`, `dob` ,`active`) VALUES (NULL, ?, ?, ?, ?, ?, ?, 0)", array($email, $password, $voornaam, $achternaam, $usr, $dob));
                    echo json_encode(array('success' => 1));
                    break;
                } else {
                    echo json_encode(array('success' => 0, 'error' => $geg['result']));
                    break;
                }
            } 
        }
        echo json_encode(array('success' => 0));
        break;
} 
?>