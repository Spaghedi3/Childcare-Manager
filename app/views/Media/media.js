document.addEventListener('DOMContentLoaded', function () {

    var type = new URLSearchParams(window.location.search).get('type') || 'image';
    fetchData(type);

    document.getElementById('file-input').addEventListener('change', function () {
        var file = this.files[0];
        var formData = new FormData();
        formData.append('file', file);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/media?type=' + type, true);
        xhr.setRequestHeader('Credentials', 'include')
        xhr.onload = function () {
            if (xhr.status == 200) {
                console.log('Uploaded successfully');
                fetchData(type);
            } else {
                console.log('Failed to upload');
                alert('Failed to upload');
            }
        };
        xhr.send(formData);
    });
});

function fetchData(type) {
    var xhr = new XMLHttpRequest();
    var url = "/api/media?type=" + type;
    xhr.open("GET", url, true);
    xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhr.withCredentials = true;

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            displayMedia(response, type);
        } else if (xhr.readyState === 4) {
            console.error("Error fetching data:", xhr.responseText);
        }
    };

    var postData = JSON.stringify({});
    xhr.send(postData);
}

function displayMedia(result, type) {
    var mediaContainer = document.getElementsByClassName('content')[0];
    mediaContainer.innerHTML = '';

    result.forEach(function (media) {
        if (type === 'image') {
            var link = document.createElement('a');
            link.href = media.media_link;
            link.target = '_blank';
            var img = document.createElement('img');
            img.src = media.media_link;
            img.alt = media.title;
            link.appendChild(img);
            mediaContainer.appendChild(link);
        } else if (type === 'document') {
            var link = document.createElement('a');
            link.href = media.media_link;
            link.target = '_blank';
            link.textContent = media.title;
            mediaContainer.appendChild(link);
        } else if (type === 'audio') {
            var audio = document.createElement('audio');
            audio.controls = true;
            var source = document.createElement('source');
            source.src = media.media_link;
            source.type = 'audio/mpeg';
            audio.appendChild(source);
            var audioTitle = document.createElement('p');
            audioTitle.textContent = media.title;
            mediaContainer.appendChild(audio);
            // mediaContainer.appendChild(audioTitle);
            // TODO css
        } else if (type === 'video') {
            var video = document.createElement('video');
            video.controls = true;
            var source = document.createElement('source');
            source.src = media.media_link;
            source.type = 'video/mp4';
            video.appendChild(source);
            var videoTitle = document.createElement('p');
            videoTitle.textContent = media.title;
            mediaContainer.appendChild(video);
            // mediaContainer.appendChild(videoTitle);
        }
    });
}

