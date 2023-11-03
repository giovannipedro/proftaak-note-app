<?php
    include('../php/security/session.php');
    // need to be logged in?
    $secure = true;
    include_once('../php/security/secure.php');
    include_once('../php/auth/dbClass.php');
    ?>

<?php 

if(isset($_SESSION['id'])) {
    $userInfo = $db->Req("SELECT * FROM `users` WHERE id = ?", array($_SESSION['id']))->fetch();


    $geg = $db->Req("SELECT notes.id AS noteID, notes.*, audio.*
    FROM notes
    LEFT JOIN audio ON notes.audioID = audio.id
    WHERE notes.idUser = ?
    ORDER BY notes.created DESC;
    ", array($_SESSION['id']))->fetchAll();
  }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keep Nates</title>
    <link rel="stylesheet" href="../assets/css/reset.css"/>
    <link rel="stylesheet" href="../assets/css/style.css?v=215"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100;0,9..40,200;0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800;0,9..40,900;0,9..40,1000;1,9..40,100;1,9..40,200;1,9..40,300;1,9..40,400;1,9..40,500;1,9..40,600;1,9..40,700;1,9..40,800;1,9..40,900;1,9..40,1000&display=swap" rel="stylesheet">

</head>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js" defer></script>
    <script src="../assets/js/mas.js" defer></script>
    <script src="https://kit.fontawesome.com/2557fb4e99.js" crossorigin="anonymous"></script>
    <script src="../assets/js/recorder.js?v=215" defer></script>
    <script src="../assets/js/script.js?v=215" defer></script>
    
    <body>
    <div id="container">
        <div class="page-title">
            <h1>Hey <?php echo $userInfo['voornaam']; ?> 👋🏻</h1>
            <div id="search">
                <!-- <i class="fa-solid fa-magnifying-glass"></i> -->
                <i class="fa-solid fa-arrows-rotate"></i>
            </div>
        </div>
        <div id="notes" class="masonry">
<?php
        foreach($geg as $i) {
                  echo '
                  <div notes-id="'.$i['noteID'].'"class="note '.$i['color'].'">
                    <p class="note-name">'.$i['name'].'</p>
                    <div class="note-container"><p>'.$i['content'].'</p>
                    '; 
                    
                    if($i['audioID'] != 0) {
                        
                    
                        echo '
                        <div class="audio-container">
                        
                                <div class="customAudioPlayer">
                                <audio class="audioPlayer" preload="auto">
                                    <source src="https://notes.alexvanrooij.nl/'.$i['audioPath'].'" type="audio/mp3">
                                </audio>
                    
                                <div class="controls">
                                    <i class="fa-solid fa-play playButton"></i>
                                    <i class="fa-solid fa-pause pauseButton"></i>
                                   
                                </div>
                            </div>
                        </div>';
                    }
                    echo '
                    </div>
                    <p class="note-date">'.$i['created'].'</p>
                    </div>';
              }


              ?>


              




             

















        </div>
    </div>
    <div id="add"> <p class="action add">+</p>
              <form type="POST" class="create-note none"> 
                <div class="nav">
                    <div>
                        <img class="action" id="back" src="assets/img/svg/arrow-left.svg" alt="go back">
                    </div>
                    <div>
                        <img class="action" id="edit" src="assets/img/svg/arrow.svg" alt="go back">
                    </div>
                </div>
                <div>
                    <input id="note-title" type="text" placeholder="Title.." class="title"></p>
                    <p id="note-date" class="date">Sun 13:00</p>
                </div>
                
                <textarea id="note-text" type="text" class="content" placeholder="Notes..."></textarea>
                <div class="audioContainer">
                    <audio id="audioPlayer" controls style="display: none;"></audio>
                </div>
                <div class="record active" id="startRecording"><i class="fa-solid fa-microphone"></i></div>
                <div class="record" id="stopRecording"><i class="fa-solid fa-stop"></i></div>


            </form>







    </div>


















    <div id="editNote" class="none" >
        <form type="POST" class="create-note "> 
        <div class="nav">
            <div>
                <img class="action" id="back" src="assets/img/svg/arrow-left.svg" alt="go back">
            </div>
            <div>
                <img class="action" id="edit" src="assets/img/svg/arrow.svg" alt="go back">
            </div>
        </div>
        <div>
            <input id="note-title" type="text" placeholder="Title.." class="title"></p>
            <p id="note-date" class="date">Sun 13:00</p>
        </div>
        
        <textarea id="note-text" type="text" class="content" placeholder="Notes..."></textarea>
        <div class="audioContainer">
            <audio id="editAudioPlayer" controls style="display: none;"></audio>
            <div class="deleteAudio none" id="deleteAudio"><i class="fa-solid fa-trash-can"></i></div>
        </div>
        <!-- <div class="record active" id="startRecording"><i class="fa-solid fa-microphone"></i></div>
        <div class="record" id="stopRecording"><i class="fa-solid fa-stop"></i></div> -->
        <div class="delete" id="deleteNote"><i class="fa-solid fa-trash-can"></i></div>

    </form>
    </div>













</body>
</html>