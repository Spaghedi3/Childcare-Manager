document.addEventListener('DOMContentLoaded', async function () {
    var type = new URLSearchParams(window.location.search).get('type') || 'image';
    await fetchData(type);

    document.getElementById('file-input').addEventListener('change', async function () {
        var file = this.files[0];
        var formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/api/media?type=' + type, {
                method: 'POST',
                body: formData,
                credentials: 'include'
            });

            if (response.ok) {
                console.log('Uploaded successfully');
                const data = await response.json();
                await fetchDataById(type, data.id);
            } else {
                console.log('Failed to upload');
                alert('Failed to upload');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });
});

async function fetchData(type) {
    try {
        const response = await fetch('/api/media?type=' + type, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json;charset=UTF-8'
            },
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            displayMedia(data, type);
        } else {
            throw new Error('Error fetching data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function fetchDataById(type, id) {
    try {
        const response = await fetch('/api/media?id=' + id, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json;charset=UTF-8'
            },
            credentials: 'include'
        });

        if (response.ok) {
            const data = await response.json();
            displayMedia(data, type, true);
        } else {
            throw new Error('Error fetching data by id');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
function displayMedia(result, type, appendNew = false) {
    var mediaContainer = document.getElementsByClassName('content')[0];

    if (!appendNew) {
        mediaContainer.innerHTML = '';
    }

    // Check if the result is an array and append each item individually
    if (Array.isArray(result)) {
        result.forEach(function (media) {
            appendMedia(media, type, mediaContainer);
        });
    } else {
        // If the result is a single item, append it directly
        appendMedia(result, type, mediaContainer);
    }
}

function appendMedia(media, type, container) {
    if (type === 'image') {
        var link = document.createElement('a');
        link.href = media.media_link;
        link.target = '_blank';
        var img = document.createElement('img');
        img.src = media.media_link;
        img.alt = media.title;
        link.appendChild(img);
        container.appendChild(link);
    } else if (type === 'document') {
        var link = document.createElement('a');
        link.href = media.media_link;
        link.target = '_blank';
        link.textContent = media.title;
        container.appendChild(link);
    } else if (type === 'audio') {
        var audio = document.createElement('audio');
        audio.controls = true;
        var source = document.createElement('source');
        source.src = media.media_link;
        source.type = 'audio/mpeg';
        audio.appendChild(source);
        var audioTitle = document.createElement('p');
        audioTitle.textContent = media.title;
        container.appendChild(audio);
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
        container.appendChild(video);
    }
}
