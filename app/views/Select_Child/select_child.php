<div class="menu-container">
  <div class="select-child-container">
    <h2>Select Child Profile</h2>
    <div class="child-profile" id="child-profile"></div>
    <button class="add-child-button">+</button>
  </div>
</div>

  <script>
 document.addEventListener('DOMContentLoaded', () => {
  const addButton = document.querySelector('.add-child-button');
  const childProfileContainer = document.getElementById('child-profile');
  if (!childProfileContainer) {
    console.error('Child profile container not found');
    return;
  }
  let childProfiles = JSON.parse(localStorage.getItem('childProfiles')) || [];
  let childCount = childProfiles.length;

  const renderChildProfiles = () => {
    childProfileContainer.innerHTML = '';
    childProfiles.forEach((profile, index) => {
      const newChildDiv = document.createElement('div');
      newChildDiv.className = 'child-profile-item';

      const childName = document.createElement('h3');
      childName.textContent = profile;
      newChildDiv.appendChild(childName);

      const nameEditInput = document.createElement('input');
      nameEditInput.type = 'text';
      nameEditInput.className = 'name-edit-input';
      nameEditInput.value = profile;
      nameEditInput.style.display = 'none';
      newChildDiv.appendChild(nameEditInput);

      const childImgDiv = document.createElement('div');
      childImgDiv.className = 'child-img-div';
      const childImg = document.createElement('img');
      childImg.src = '/app/views/images/logo.ico';
      childImg.alt = 'Child Image';
      childImgDiv.appendChild(childImg);
      newChildDiv.appendChild(childImgDiv);

      const editButton = document.createElement('button');
      editButton.className = 'edit-name';
      editButton.textContent = 'Edit name';
      editButton.addEventListener('click', () => toggleEditNameInput(newChildDiv, nameEditInput));
      newChildDiv.appendChild(editButton);

      const changeImageButton = document.createElement('button');
      changeImageButton.className = 'change-image';
      changeImageButton.textContent = 'Change image';
      changeImageButton.addEventListener('click', () => changeChildImage(index));
      newChildDiv.appendChild(changeImageButton);

      const deleteButton = document.createElement('button');
      deleteButton.className = 'delete-child-button';
      deleteButton.textContent = 'x';
      deleteButton.addEventListener('click', () => deleteChildProfile(index));
      newChildDiv.appendChild(deleteButton);

      childProfileContainer.appendChild(newChildDiv);
    });
  };

  const addChildProfile = () => {
    childCount++;
    const newChild = `Child ${childCount}`;
    childProfiles.push(newChild);
    localStorage.setItem('childProfiles', JSON.stringify(childProfiles));
    renderChildProfiles();
  };

  const deleteChildProfile = (index) => {
    childProfiles.splice(index, 1);
    localStorage.setItem('childProfiles', JSON.stringify(childProfiles));
    renderChildProfiles();
  };

  const toggleEditNameInput = (childDiv, nameInput) => {
    const childName = childDiv.querySelector('h3');
    const editButton = childDiv.querySelector('.edit-name');

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
      const index = Array.from(childDiv.parentNode.children).indexOf(childDiv);
      childProfiles[index] = nameInput.value;
      localStorage.setItem('childProfiles', JSON.stringify(childProfiles));
    }
  };

  const changeChildImage = (index) => {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.onchange = (event) => {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const childImg = childProfileContainer.querySelectorAll('.child-profile-item')[index].querySelector('img');
          childImg.src = e.target.result;
        };
        reader.readAsDataURL(file);
      }
    };
    input.click();
  };

  addButton.addEventListener('click', () => {
    console.log('Add button clicked');
    addChildProfile();
  });

  renderChildProfiles();
});

</script>

</div>
