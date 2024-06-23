document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.querySelector('.add-child-button');
    const childProfileContainer = document.getElementById('child-profile');

    if (!childProfileContainer) {
        console.error('Child profile container not found');
        return;
    }

    const fetchChildProfiles = async () => {
        try {
            const response = await fetch('/api/children', { method: 'GET' });
            const responseText = await response.text();
            const data = JSON.parse(responseText);

            renderChildProfiles(data);
        } catch (error) {
            console.error('Error fetching child profiles:', error);
        }
    };

    const renderChildProfiles = (profiles) => {
        childProfileContainer.innerHTML = '';
        profiles.forEach((profile, index) => {
            createProfileCard(profile, index);
        });
    };

    const createProfileCard = (profile, index) => {
        const newChildDiv = document.createElement('div');
        newChildDiv.className = 'child-profile-item';
        newChildDiv.dataset.id = profile.id;

        const childName = document.createElement('h3');
        childName.textContent = profile.name;
        newChildDiv.appendChild(childName);

        const nameEditInput = document.createElement('input');
        nameEditInput.type = 'text';
        nameEditInput.className = 'name-edit-input';
        nameEditInput.value = profile.name;
        nameEditInput.style.display = 'none';
        nameEditInput.addEventListener('click', (event) => {
            event.stopPropagation();
        });
        newChildDiv.appendChild(nameEditInput);

        const childImgDiv = document.createElement('div');
        childImgDiv.className = 'child-img-div';
        const childImg = document.createElement('img');
        childImg.src = profile.profile_picture_path;
        childImg.alt = 'Child Image';
        childImgDiv.appendChild(childImg);
        newChildDiv.appendChild(childImgDiv);

        const editButton = document.createElement('button');
        editButton.className = 'edit-name';
        editButton.textContent = 'Edit name';
        editButton.addEventListener('click', (event) => {
            event.stopPropagation();
            toggleEditNameInput(newChildDiv, nameEditInput);
        });
        newChildDiv.appendChild(editButton);

        const changeImageButton = document.createElement('button');
        changeImageButton.className = 'change-image';
        changeImageButton.textContent = 'Change image';
        changeImageButton.addEventListener('click', (event) => {
            event.stopPropagation();
            changeChildImage(newChildDiv);
        });
        newChildDiv.appendChild(changeImageButton);

        const deleteButton = document.createElement('button');
        deleteButton.className = 'delete-child-button';
        deleteButton.textContent = 'x';
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation();
            if (confirm("Are you sure you want to delete this profile?")) {
                deleteChildProfile(newChildDiv.dataset.id);
            }
        });
        newChildDiv.appendChild(deleteButton);

        newChildDiv.addEventListener('click', () => {
            document.cookie = `childId=${profile.id}; path=/`;
            window.location.href = `/childProfile?id=${profile.id}`;
        });

        childProfileContainer.appendChild(newChildDiv);
    };

    const addChildProfile = async () => {
        try {
            const response = await fetch('/api/children', { method: 'POST' });
            const newProfile = await response.json();
            createProfileCard(newProfile, document.querySelectorAll('.child-profile-item').length);
        } catch (error) {
            console.error('Error adding child profile:', error);
        }
    };

    const deleteChildProfile = async (id) => {
        try {
            const response = await fetch(`/api/children/${id}`, { method: 'DELETE', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } });
            const result = await response.json();
            if (result.success) {
                fetchChildProfiles();
            } else {
                console.error('Error deleting child profile:', result.error);
            }
        } catch (error) {
            console.error('Error deleting child profile:', error);
        }
    };

    const toggleEditNameInput = (childDiv, nameInput) => {
        const childName = childDiv.querySelector('h3');
        const editButton = childDiv.querySelector('.edit-name');
        const id = childDiv.dataset.id;

        if (nameInput.style.display === 'none') {
            childName.style.display = 'none';
            nameInput.style.display = 'block';
            nameInput.focus();
            editButton.textContent = 'Save';
        } else {
            childName.textContent = nameInput.value;
            childName.style.display = 'block';
            nameInput.style.display = 'none';
            editButton.textContent = 'Edit name';
            const updateData = {
                id: id,
                name: nameInput.value
            };
            updateChildProfile(id, updateData);
        }
    };

    const updateChildProfile = async (id, updateData) => {
        try {
            const response = await fetch(`/api/children/${id}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(updateData)
            });
            const result = await response.json();
            if (!result.success) {
                console.error('Error updating child profile:', result.error);
            }
        } catch (error) {
            console.error('Error updating child profile:', error);
        }
    };

    const changeChildImage = (childDiv) => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async (event) => {
            const file = event.target.files[0];
            if (file) {
                try {
                    const reader = new FileReader();
                    reader.onload = async (e) => {
                        const childImg = childDiv.querySelector('img');
                        childImg.src = e.target.result;

                        // to base64
                        const imageData = e.target.result.split(',')[1];

                        // update profile picture 
                        const id = childDiv.dataset.id;
                        const updateData = {
                            id: id,
                            profile_picture: {
                                name: file.name,
                                type: file.type,
                                data: imageData
                            }
                        };

                        await updateChildProfile(id, updateData);
                    };
                    reader.readAsDataURL(file);
                } catch (error) {
                    console.error('Error reading file or updating profile:', error);
                }
            }
        };
        input.click();
    };

    addButton.addEventListener('click', () => {
        addChildProfile();
    });

    fetchChildProfiles();
});
