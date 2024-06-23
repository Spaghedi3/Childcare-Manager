function appendMedia(media, type, container) {
    var blob = dataURItoBlob(media.media_data);
    var url = URL.createObjectURL(blob);
    var mediaElement = document.createElement('div');
    mediaElement.dataset.id = media.id;
    container.appendChild(mediaElement);

    if (type === 'image') {
        var link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        var img = document.createElement('img');
        img.src = url;
        img.alt = media.title;
        link.appendChild(img);
        mediaElement.appendChild(link);
    } else if (type === 'document') {
        var link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        link.textContent = media.title;
        mediaElement.appendChild(link);
    } else if (type === 'audio') {
        var audio = document.createElement('audio');
        audio.controls = true;
        var source = document.createElement('source');
        source.src = url;
        source.type = 'audio/mpeg';
        audio.appendChild(source);
        var audioTitle = document.createElement('p');
        audioTitle.textContent = media.title;
        mediaElement.appendChild(audioTitle);
        mediaElement.appendChild(audio);
    } else if (type === 'video') {
        var video = document.createElement('video');
        video.controls = true;
        var source = document.createElement('source');
        source.src = url;
        source.type = 'video/mp4';
        video.appendChild(source);
        var videoTitle = document.createElement('p');
        videoTitle.textContent = media.title;
        mediaElement.appendChild(video);
    }
}

function dataURItoBlob(dataURI) {
    var byteString = atob(dataURI.split(',')[1]);
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
    var ab = new ArrayBuffer(byteString.length);
    var ia = new Uint8Array(ab);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    return new Blob([ab], {type: mimeString});
}
