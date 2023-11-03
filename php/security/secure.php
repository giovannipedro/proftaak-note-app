<?php
if($secure===true) {
    if($_SESSION['loggedin']===false || !isset($_SESSION['id'])) {
        header("Location: /");
        die;
    }
}