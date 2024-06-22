document.addEventListener('DOMContentLoaded', async function () {
    var type = new URLSearchParams(window.location.search).get('type') || 'image';
    await fetchData(type);

    const fileInput = document.getElementById('file-input');
    const deleteButton = document.getElementById('delete-button');
    const deleteMessage = document.getElementById('delete-message');

    fileInput.addEventListener('change', handleFileUpload);
    deleteButton.addEventListener('click', handleDeleteMode);

    async function handleFileUpload() {
        const file = fileInput.files[0];
        const formData = new FormData();
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
                if (data.type === type) {
                    await fetchDataById(type, data.id);
                }
            } else {
                console.log('Failed to upload');
                alert('Failed to upload');
            }
        } catch (error) {
            console.error('Error:', error);
        }

        // Reset file input to allow re-upload of the same file if needed
        fileInput.value = '';
    }

    function handleDeleteMode() {
        deleteMessage.style.display = 'block';

        document.addEventListener('mouseover', tintElement);
        document.addEventListener('mouseout', untintElement);
        document.addEventListener('click', deleteMedia, true);

        function tintElement(event) {
            const target = event.target.closest('[data-id]');
            if (target) {
                target.style.filter = 'brightness(0.3)';
            }
        }

        function untintElement(event) {
            const target = event.target.closest('[data-id]');
            if (target) {
                target.style.filter = '';
            }
        }

        async function deleteMedia(event) {
            event.preventDefault();
            const target = event.target.closest('[data-id]');
            if (target) {
                const id = target.dataset.id;
                try {
                    const response = await fetch(`/api/media/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json;charset=UTF-8'
                        },
                        credentials: 'include'
                    });

                    if (response.ok) {
                        target.remove();
                        console.log('Deleted successfully');
                    } else {
                        throw new Error('Failed to delete');
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            resetDeleteMode();
        }

        function resetDeleteMode() {
            deleteMessage.style.display = 'none';

            document.removeEventListener('mouseover', tintElement);
            document.removeEventListener('mouseout', untintElement);
            document.removeEventListener('click', deleteMedia, true);
        }
    }

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
        const mediaContainer = document.getElementsByClassName('content')[0];

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
});
