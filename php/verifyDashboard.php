<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);

include_once("auth/dbClass.php");
include_once("security/functions.php");


(isset($_REQUEST['action'])) ? ($action = $_REQUEST['action']) : ($action = 0);

switch ($action) {
    case 1:

        if(isset($_REQUEST['noteTitle']) && isset($_REQUEST['noteTest'])) {
            $colors = ['a', 'b', 'c', 'd', 'e'];

            
            $notesTitle = (string)$_REQUEST['noteTitle'];
            $notesText = (string)$_REQUEST['noteTest'];
            if(isset($_FILES['audioData']['tmp_name'])) {
                $audioData = $_FILES['audioData']['tmp_name'];
            }


            if(isset($audioData)) {
                $audioName = time();
                $audioName = $audioName . ".mp3"; 
                move_uploaded_file($audioData, "../audio/$audioName"); 



                $geg = $db->Req("INSERT INTO `audio` (`id`, `idUser`,`audioPath`,`audioName`) VALUES (NULL, ?, ?, ?)", array($_SESSION['id'], "audio/" . $audioName, $audioName));
                $id = $db->Req('SELECT LAST_INSERT_ID() as lastID', array())->fetch();



                $geg = $db->Req("INSERT INTO `notes` (`id`, `idUser`, `name`, `created`, `content`, `color`, `audioID`) VALUES 
                    (NULL, ?,?,CURRENT_TIMESTAMP(),?,?,?)", array($_SESSION['id'], $notesTitle, $notesText, $colors[random_int(0, 4)], $id['lastID']));
            } else {
                $geg = $db->Req("INSERT INTO `notes` (`id`, `idUser`, `name`, `created`, `content`, `color`) VALUES (NULL, ?,?,CURRENT_TIMESTAMP(),?,?)", array($_SESSION['id'], $notesTitle, $notesText, $colors[random_int(0, 4)]));
            }


            if (isset($geg)) {
                $geg = $db->Req('SELECT LAST_INSERT_ID() as lastID', array())->fetch();

                $geg = $db->Req("SELECT 
                notes.id AS noteID, 
                notes.color AS noteColor,
                notes.name AS noteName,
                notes.content AS noteContent,
                audio.id AS audioID,
                audio.audioPath as audioPath,
                notes.created AS noteCreated
                FROM notes
                LEFT JOIN audio ON notes.audioID = audio.id
                WHERE notes.idUser = ?
                AND notes.id = ?
                ORDER BY notes.created DESC;
                ", array($_SESSION['id'], $geg['lastID']))->fetchAll();
                echo json_encode($geg);
             
            }
        } else {
            echo 0;
        }

         
        break;
    case 2:
        // $geg = $db->Req("SELECT * from notes WHERE `idUser` = ? ORDER BY created DESC", array($_SESSION['id']))->fetchAll();


        $geg = $db->Req("SELECT notes.id AS noteID, notes.*, audio.*
        FROM notes
        LEFT JOIN audio ON notes.audioID = audio.id
        WHERE notes.idUser = ?
        ORDER BY notes.created DESC;
        ", array($_SESSION['id']))->fetchAll();
        echo json_encode($geg);
        break;
    case 3:
        $geg = $db->Req("SELECT 
                notes.id AS noteID, 
                notes.color AS noteColor,
                notes.name AS noteName,
                notes.content AS noteContent,
                audio.id AS audioID,
                audio.audioPath as audioPath,
                notes.created AS noteCreated
                FROM notes
                LEFT JOIN audio ON notes.audioID = audio.id
                WHERE notes.idUser = ?
                AND notes.id = ?
                ORDER BY notes.created DESC;
                ", array($_SESSION['id'], $_REQUEST['id']))->fetch();
                echo json_encode($geg);


        // $geg = $db->Req("SELECT notes.id AS noteID, notes.*, audio.*
        // FROM notes
        // LEFT JOIN audio ON notes.audioID = audio.id
        // WHERE notes.idUser = ? AND
        // notes.id = ?
        // ORDER BY notes.created DESC;
        // ", array($_SESSION['id'], $_REQUEST['id']))->fetch();
        // echo json_encode($geg);

        break;
    case 4: 
        $geg = $db->Req("DELETE notes, audio
        FROM notes
        LEFT JOIN audio ON notes.audioID = audio.id
        WHERE notes.id = ? AND notes.idUser = ?", array($_REQUEST['id'], $_SESSION['id']));
        echo 1;
        break;
    case 5:

        $geg = $db->Req("UPDATE `notes` SET `name`=?,`created`=CURRENT_TIMESTAMP(),`content`=? WHERE id = ? AND idUser = ?", array($_REQUEST['title'] , $_REQUEST['content'] , $_REQUEST['id'], $_SESSION['id']));
        echo 1;

        break;
    case 6:
        $geg = $db->Req("UPDATE `notes` SET `audioID`=0,`created`=CURRENT_TIMESTAMP() WHERE id = ? AND idUser = ?", array($_REQUEST['id'], $_SESSION['id']));
      echo 1;

        break;
}