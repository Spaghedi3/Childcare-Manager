document.addEventListener('DOMContentLoaded', async function () {
    var type = new URLSearchParams(window.location.search).get('type') || 'image';
    await fetchData(type);

    document.getElementById('file-input').addEventListener('change', async function () {
        var file = this.files[0];
        var formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch('/api/media', {
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
        const response = await fetch('/api/media/' + id, {
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

    if (Array.isArray(result)) {
        result.forEach(function (media) {
            appendMedia(media, type, mediaContainer);
        });
    } else {
        appendMedia(result, type, mediaContainer);
    }
}