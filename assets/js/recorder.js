$(document).ready(function () {
    const startRecordingButton = $('#startRecording');
    const stopRecordingButton = $('#stopRecording');
    const audioPlayer = $('#audioPlayer');
    let mediaRecorder;
    let audioChunks = [];

    startRecordingButton.on('click', async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    audioChunks.push(event.data);
                }
            };

            mediaRecorder.onstop = () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayer.attr('src', audioUrl);
                audioPlayer.css({
                  'display': 'flex'
                });
                window.audioBlob = audioBlob;
                audioChunks = [];
            };

            mediaRecorder.start();
            startRecordingButton.toggleClass('active');
            stopRecordingButton.toggleClass('active');
        } catch (error) {
            console.error('Error accessing microphone:', error);
        }
    });

    stopRecordingButton.on('click', () => {
        mediaRecorder.stop();
        startRecordingButton.toggleClass('active');
        stopRecordingButton.toggleClass('active');
    });
});