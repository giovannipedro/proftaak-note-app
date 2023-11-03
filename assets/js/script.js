
$(document).ready(function () {
    refreshAudioEvent();
    function refreshAudioEvent() {
      $(".customAudioPlayer").each(function(index, element) {
        $(element).unbind('click');
      });

      $('.pauseButton').css('display', 'none');
      $(".customAudioPlayer").each(function(index, element) {
      $(element).on("click", function(e) {
         

          const audio = $(element).find('.audioPlayer')[0];
          $(audio).on("timeupdate", function() { 
            var progressPercentage = (audio.currentTime / audio.duration) * 100;
            if(progressPercentage == 100) {
                $(element).find('.controls .pauseButton').toggle();
                $(element).find('.controls .playButton').toggle();
                
                progressPercentage = 0;
                $(audio).unbind('timeupdate');
            }
          });


          if ($(e.target).hasClass('playButton')) {
              $(e.target).toggle();
              console.log($(element));
              $(element).find('.pauseButton').toggle();
    
              audio.play();

          }
          if ($(e.target).hasClass('pauseButton')) {
              $(e.target).toggle();
              console.log($(element));
              $(element).find('.playButton').toggle();
              
            
              audio.pause();

              }

          });
      });

    }









  $('textarea').on('input', function () {
      $(this).css('height', 'auto'); 
      $(this).css('height', this.scrollHeight + 'px'); 
  });
  $('textarea').trigger('input');

  let $grid = $('#notes').masonry({
    itemSelector: '.note',
    percentPosition: true,
    gutter: 20
  });
  $grid.masonry();
  $('#add').on('click', '.action', function () {
    const $clickedButton = $(this);
    if($clickedButton.hasClass('add')) {
      $('#add').removeClass('scale-out-br');
      $('#add').addClass('scale-in-br');

      const audioPlayer = $('#audioPlayer');
      audioPlayer.attr('src', "");
      audioPlayer.css({'display': 'none'});
      window.audioBlob = [];

      $clickedButton.addClass('none');
      $('#add .create-note').removeClass('none');
      setTimeout(function (){
        $('#container').addClass('hidden');
      }, 100);

    } else if ($clickedButton.attr('id') == "back") {
      $('#add').toggleClass('scale-in-br scale-out-br');
      $('.action.add').removeClass('none');
      $('#add .create-note').addClass('none');
      $('#container').removeClass('hidden');
    } else if ($clickedButton.attr('id') == "edit") {
      var formData = new FormData();
      formData.append('audioData', window.audioBlob);
      formData.append('noteTitle', $('#note-title').val());
      formData.append('noteTest', $('#note-text').val());

        $.ajax({
          url: '../../php/verifyDashboard.php?action=1',
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
              if(response != 0) {
                addLastItem(response);
              }
          }
        });



      $('#add').toggleClass('scale-in-br scale-out-br');
      $('.action.add').removeClass('none');
      $('#add .create-note').addClass('none');
      $('#container').removeClass('hidden');
    }
    removeText();
    updateDateDisplay();
    $('textarea').trigger('input');


  });





function sendAjax(type, url, data, successCallback) {
  $.ajax({
      type: type,
      url: url,
      data: data,
      success: function(response) {
          var jsonData = JSON.parse(response);
              if (successCallback) {
                  successCallback(jsonData);
              }
          
      }
  });
}

function removeText() {
  $('#note-title, #note-text').val("");
}

function getCurrentFormattedDate() {
  const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  const now = new Date();
  const dayOfWeek = daysOfWeek[now.getDay()];
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');

  return `${dayOfWeek} ${hours}:${minutes}`;

}

function updateDateDisplay() {
  const formattedDate = getCurrentFormattedDate();
  $('.date').text(formattedDate);
}

function reloadItems() {
  $('#search i').toggleClass('rotated');
  console.log('reloaded');
  sendAjax("POST", "../../php/verifyDashboard.php?action=2", { no: 0 }, function(responseData) {
    $grid.masonry( 'remove', $('.note') )
    .masonry('layout');
    responseData.reverse();

    $.each(responseData, function(index, element) {
      let extra = '';

      if(element['audioID'] != 0) {
        extra = `
        <div class="audio-container">
          <div class="customAudioPlayer">
              <audio class="audioPlayer" preload="auto">
                  <source src="https://notes.alexvanrooij.nl/${element['audioPath']}" type="audio/mp3">
              </audio>
              <div class="controls">
                  <i class="fa-solid fa-play playButton"></i>
                  <i class="fa-solid fa-pause pauseButton" ></i>
                  
              </div>
          </div>
      </div>

        `;

      }
      let $items = $(`
      <div notes-id='${element['noteID']}' class='note ${element['color']}'>
        <p class='note-name'>${element['name']}</p>
        <div class="note-container"><p>${element['content']}</p>
        ${extra}
        </div>
      <p class="note-date">${element['created']}</p>
      </div>`);
      $grid.prepend( $items )
      .masonry('prepended', $items);
      refreshAudioEvent();
    })
  });
};




  $('#notes').on('click', '.note', function (e) {
    console.log('test');
    e.preventDefault();
    if($(e.target).is('.playButton, .pauseButton')) {
      console.log(10);
      e.preventDefault();
      return;
    } else {
      console.log(11);
      $.ajax({
        url: '../../php/verifyDashboard.php?action=3',
        type: 'POST',
        data:  { id: $(this).attr('notes-id') },
        success: function(response) {
          const noteInfo = JSON.parse(response);
          console.log(noteInfo['noteName']);
          updateDateDisplay();
          $('textarea').trigger('input');
          $('#deleteNote').attr('note-id', noteInfo['noteID']);
          $('#editNote #note-title').val(noteInfo['noteName']);
          $('#editNote #note-text').val(noteInfo['noteContent']);
          if(noteInfo['audioPath'] != undefined) {
            $('#editAudioPlayer').attr('src', `https://notes.alexvanrooij.nl/${noteInfo['audioPath']}`);
            $('#editAudioPlayer').css({
              'display': 'flex'
            });
            $('#deleteAudio').removeClass('none');
          } else {
            $('#deleteAudio').addClass('none');
            $('#editAudioPlayer').attr('src', ``);
            $('#editAudioPlayer').css({
              'display': 'none'
            });
          }
        }
      });
      $('#editNote').removeClass('none');
    }
  
  });



$('#editNote').on('click', '#back', function () {
  $('#editNote').addClass('none');
});

$('#editNote').on('click', '#deleteNote', function () {
  console.log($('#deleteNote').attr('note-id'));
  $.ajax({
    url: '../../php/verifyDashboard.php?action=4',
    type: 'POST',
    data:  { id: $('#deleteNote').attr('note-id') },
    success: function(response) {
      console.log(response);
      if(response == 1) {
        console.log(1);
        let item = $(`div[notes-id="${$('#deleteNote').attr('note-id')}"]`);

        $grid.masonry( 'remove', item )
        .masonry('layout');

        $('#editNote').addClass('none');
      }
      console.log(2);
    }
  });
});

$('#deleteAudio').on('click', function(e) {
 $.ajax({
    url: '../../php/verifyDashboard.php?action=6',
    type: 'POST',
    data:  { 
      id: $('#deleteNote').attr('note-id')
    },
    success: function(response) {
      console.log(response);
      if(response == 1) {
        const audioPlayer = $('#editAudioPlayer');
        audioPlayer.attr('src', "");
        audioPlayer.css({'display': 'none'});
        $('#deleteAudio').addClass('none');
        reloadItems();
        refreshAudioEvent();
      }
    }
  });

});




$('#editNote').on('click', '#edit', function () {
  $.ajax({
    url: '../../php/verifyDashboard.php?action=5',
    type: 'POST',
    data:  { 
      title: $('#editNote #note-title').val(),
      content: $('#editNote #note-text').val(),
      id: $('#deleteNote').attr('note-id')
    },
    success: function(response) {
      console.log(response);
      if(response == 1) {
        $('#editNote').addClass('none');
        reloadItems();
      }
    }
  });
});



$('body').on('click', '#search', function () {
  reloadItems();
});








  function addLastItem(response) {
    response = JSON.parse(response);
    let extra = '';
      if(response[0]['audioID'] != null) {
        extra = `
        <div class="audio-container">
          <div class="customAudioPlayer">
              <audio class="audioPlayer" preload="auto">
                  <source src="https://notes.alexvanrooij.nl/${response[0]['audioPath']}" type="audio/mp3">
              </audio>
  
              <div class="controls">
                  <i class="fa-solid fa-play playButton"></i>
                  <i class="fa-solid fa-pause pauseButton" ></i>
                  
              </div>
          </div>
      </div>`;
      }
      let $items = $(`
      <div notes-id='${response[0]['noteID']}' class='note ${response[0]['noteColor']}'>
        <p class='note-name'>${response[0]['noteName']}</p>
        <div class="note-container"><p>${response[0]['noteContent']}</p>
        ${extra}
        </div>
      <p class="note-date">${response[0]['noteCreated']}</p>
      </div>`);

      $grid.prepend( $items )
      .masonry( 'prepended', $items );
      refreshAudioEvent();
      
  }
});





