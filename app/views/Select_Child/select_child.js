document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.querySelector('.add-child-button');
    const childProfileContainer = document.getElementById('child-profile');

    if (!childProfileContainer) {
        console.error('Child profile container not found');
        return;
    }

    const fetchChildProfiles = async () => {
        try {
            const response = await fetch('/getProfiles', { method: 'GET' });
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
            event.stopPropagation(); // Stop the click event from propagating to the parent div
            toggleEditNameInput(newChildDiv, nameEditInput);
        });
        newChildDiv.appendChild(editButton);

        const changeImageButton = document.createElement('button');
        changeImageButton.className = 'change-image';
        changeImageButton.textContent = 'Change image';
        changeImageButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Stop the click event from propagating to the parent div
            changeChildImage(newChildDiv);
        });
        newChildDiv.appendChild(changeImageButton);

        const deleteButton = document.createElement('button');
        deleteButton.className = 'delete-child-button';
        deleteButton.textContent = 'x';
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation(); // Stop the click event from propagating to the parent div
            deleteChildProfile(newChildDiv.dataset.id);
        });
        newChildDiv.appendChild(deleteButton);

        newChildDiv.addEventListener('click', () => {
            window.location.href = `/childProfile?id=${profile.id}`;
        });

        childProfileContainer.appendChild(newChildDiv);
    };

    const addChildProfile = async () => {
        try {
            const response = await fetch('/addProfile', { method: 'POST' });
            const newProfile = await response.json();
            createProfileCard(newProfile, document.querySelectorAll('.child-profile-item').length);
        } catch (error) {
            console.error('Error adding child profile:', error);
        }
    };

    const deleteChildProfile = async (id) => {
        try {
            const response = await fetch(`/deleteProfile?id=${id}`, { method: 'GET', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' } });
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
            updateChildProfile(id, { name: nameInput.value });
        }
    };

    const updateChildProfile = async (id, updateData) => {
        let formData;

        if (updateData.profile_picture) {
            formData = new FormData();
            formData.append('id', id);
            formData.append('profile_picture', updateData.profile_picture);
        } else {
            formData = JSON.stringify({ id, ...updateData });
        }

        try {
            const response = await fetch('/updateProfile', {
                method: 'POST',
                headers: updateData.profile_picture ? {} : { 'Content-Type': 'application/json' },
                body: formData
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
        input.onchange = (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const childImg = childDiv.querySelector('img');
                    childImg.src = e.target.result;
                    const id = childDiv.dataset.id;
                    updateChildProfile(id, { profile_picture: file });
                };
                reader.readAsDataURL(file);
            }
        };
        input.click();
    };

    addButton.addEventListener('click', () => {
        addChildProfile();
    });

    fetchChildProfiles();
});
