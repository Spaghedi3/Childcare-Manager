async function fetchDataById(id, container) {
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
            await appendMedia(data, data.type, container);
        } else {
            throw new Error('Error fetching data by id');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

const getTagsArray = (container) => {
    const checkboxes = container.querySelectorAll('.checkboxes-container input[type="checkbox"]');
    const tags = [];

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            tags.push(checkbox.name);
        }
    });

    return tags;
};

const fetchPostTags = async (postId) => {

    try {
        const response = await fetch(`/api/posts/${postId}`, { method: 'GET' });
        if (!response.ok) {
            throw new Error('Failed to fetch tags');
        }
        result = await response.json();
        return result[0].tags;
    } catch (error) {
        console.error('Error fetching tags:', error);
        return [];
    }
};

document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.querySelector('#addMemoryButton');

    const createTimelineEntryCard = async (post = {}) => {
        const container = document.createElement('div');
        container.classList.add('timeline-entry', 'container');
        container.dataset.id = post.id || '';

        const dateHeader = document.createElement('h3');
        const currentDate = post.date || new Date().toLocaleDateString('en-US');
        dateHeader.textContent = currentDate;
        container.appendChild(dateHeader);

        const titleElement = document.createElement('h4');
        titleElement.classList.add('title');
        titleElement.textContent = post.title || 'Enter title here...';
        container.appendChild(titleElement);

        if (post.mediaId !== null && post.mediaId !== undefined) {
            await fetchDataById(post.mediaId, container);
        }

        const timelineEntries = document.querySelector('.timeline-entries');
        timelineEntries.appendChild(container);

        const contentElement = document.createElement('p');
        contentElement.classList.add('content');
        contentElement.textContent = post.content || 'Enter content here...';
        container.appendChild(contentElement);

        const editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.classList.add('edit-button');
        container.appendChild(editButton);

        const saveButton = document.createElement('button');
        saveButton.textContent = 'Save';
        saveButton.classList.add('save-button');
        saveButton.style.display = 'none';
        container.appendChild(saveButton);

        const shareButton = document.createElement('button');
        shareButton.textContent = 'Share';
        shareButton.classList.add('share-button');
        shareButton.addEventListener('click', () => {
            window.location.href = "/timeline?id=" + container.dataset.id;
        });
        container.appendChild(shareButton);

        const deleteButton = document.createElement('button');
        deleteButton.className = 'delete-button';
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', () => {
            deletePost(post.id, container);
        });
        container.appendChild(deleteButton);

        editButton.addEventListener('click', () => {
            enterEditMode(container);
        });

        saveButton.addEventListener('click', () => {
            saveChanges(container);
        });

        return container;
    };

    const enterEditMode = async (container) => {
        const titleElement = container.querySelector('.title');
        const contentElement = container.querySelector('.content');
        const editButton = container.querySelector('.edit-button');
        const saveButton = container.querySelector('.save-button');
        const deleteButton = container.querySelector('.delete-button');
        const shareButton = container.querySelector('.share-button');
        const postId = container.dataset.id;

        const titleInput = document.createElement('input');
        titleInput.type = 'text';
        titleInput.value = titleElement.textContent;
        titleElement.textContent = '';
        titleElement.appendChild(titleInput);

        const contentInput = document.createElement('textarea');
        contentInput.value = contentElement.textContent;
        contentElement.textContent = '';
        contentElement.appendChild(contentInput);

        const checkboxesContainer = document.createElement('div');
        checkboxesContainer.classList.add('checkboxes-container');

        const checkboxes = [
            { label: 'Parents', value: 'parents' },
            { label: 'Grandparents', value: 'grandparents' },
            { label: 'Siblings', value: 'siblings' },
            { label: 'Friends', value: 'friends' }
        ];

        let tags = [];
        if (postId) {
            tags = await fetchPostTags(postId);
            console.log(tags);
        }
        checkboxes.forEach(item => {
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = item.value;
            checkbox.id = item.value;
            checkbox.checked = false;

            if (Array.isArray(tags) && tags.includes(item.value)) {
                checkbox.checked = true;
            }

            const label = document.createElement('label');
            label.textContent = item.label;
            label.htmlFor = item.value;

            checkboxesContainer.appendChild(checkbox);
            checkboxesContainer.appendChild(label);
            checkboxesContainer.appendChild(document.createElement('br'));
        });

        container.appendChild(checkboxesContainer);

        uploadButton = document.createElement('input');
        uploadButton.type = 'file';
        uploadButton.classList.add('upload-button');
        uploadButton.style.display = 'block';
        container.appendChild(uploadButton);
        uploadButton.addEventListener('change', (event) => {
            const elementsToRemove = container.querySelectorAll('a, video, audio');
            elementsToRemove.forEach(element => element.remove());

            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const media = { media_data: e.target.result, title: file.name };
                    const type = determineFileType(file.type);
                    appendMedia(media, type, container);
                };
                reader.readAsDataURL(file);
            }
        });

        function determineFileType(mimeType) {
            if (mimeType.startsWith('image/')) {
                return 'image';
            } else if (mimeType === 'application/pdf' || mimeType.startsWith('text/')) {
                return 'document';
            } else if (mimeType.startsWith('audio/')) {
                return 'audio';
            } else if (mimeType.startsWith('video/')) {
                return 'video';
            } else {
                return 'document';
            }
        }

        editButton.style.display = 'none';
        deleteButton.style.display = 'none';
        shareButton.style.display = 'none';
        saveButton.style.display = 'block';
    };


    const saveChanges = async (container) => {
        const titleElement = container.querySelector('.title');
        const contentElement = container.querySelector('.content');
        const editButton = container.querySelector('.edit-button');
        const deleteButton = container.querySelector('.delete-button');
        const shareButton = container.querySelector('.share-button');
        const saveButton = container.querySelector('.save-button');
        const uploadButton = container.querySelector('.upload-button');
        const postId = container.dataset.id;

        const title = titleElement.querySelector('input').value.trim();
        const content = contentElement.querySelector('textarea').value.trim();
        const file = uploadButton.files[0];
        let mediaId;
        let imageUrl = 'app/views/Images/default.jpg';

        if (!title || !content) {
            alert('Please enter both a title and content.');
            return;
        }

        try {
            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                const response = await fetch('/api/media', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    console.log('Uploaded successfully');
                    const result = await response.json();
                    mediaId = result.id;
                    imageUrl = result.url;
                } else {
                    console.log('Failed to upload');
                    alert('Failed to upload');
                }
            }
        } catch (error) {
            console.error('Error uploading image:', error);
        }

        let data;

        if (mediaId !== null && mediaId !== undefined) {
            data = {
                title: title,
                content: content,
                mediaId: mediaId,
                tags: getTagsArray(container)
            };
        } else {
            data = {
                title: title,
                content: content,
                tags: getTagsArray(container)
            };
        }

        try {
            let response;
            if (postId) {
                response = await fetch(`/api/posts/${postId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
            } else {
                response = await fetch('/api/posts', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
            }
            if (response.ok) {
                console.log('Post saved successfully:', data);
            } else {
                if (response.statusText !== 'Not Modified')
                    console.error('Failed to save post:', response.statusText);
            }

            titleElement.textContent = title;
            contentElement.textContent = content;

            editButton.style.display = 'inline-block';
            deleteButton.style.display = 'inline-block';
            shareButton.style.display = 'inline-block';
            saveButton.style.display = 'none';

            container.removeChild(uploadButton);
        } catch (error) {
            console.error('Error saving memory:', error);
        }

        fetchPosts();
    };

    const fetchPosts = async () => {

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const relationship_type = urlParams.get('relationship_type');
        const id = urlParams.get('id');
        let fetchUrl = '/api/posts';
        if (id) {
            const element = document.getElementsByClassName('timeline-container')[0];
            element.remove();
            fetchUrl += `/${id}`;
        }
        if (relationship_type) {
            const element = document.getElementsByClassName('timeline-container')[0];
            element.remove();
            fetchUrl += `?relationship_type=${relationship_type}`;
        }

        try {
            const response = await fetch(fetchUrl, {
                method: 'GET',
                credentials: 'include',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            renderPosts(data);
        }
        catch (error) {
            console.error('Error fetching posts:', error);

        }
    };

    const renderPosts = (posts) => {
        const timelineEntries = document.querySelector('.timeline-entries');
        timelineEntries.innerHTML = '';
        posts.forEach(post => {
            createTimelineEntryCard(post);
        });
    };

    const deletePost = async (postId, container) => {
        try {
            const response = await fetch(`/api/posts/${postId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            if (response.ok) {
                container.remove();
            } else {
                console.error('Error deleting post:', result.error);
            }
        } catch (error) {
            console.error('Error deleting post:', error);
        }
    };

    const addPost = async () => {
        const container = await createTimelineEntryCard();
        enterEditMode(container);
    };

    fetchPosts();
    addButton.addEventListener('click', async () => await addPost());
});
