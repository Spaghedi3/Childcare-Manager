document.addEventListener('DOMContentLoaded', async () => {
    const editButtons = document.querySelectorAll('.medical_info_container button');

    const calculateAge = (dateString) => {
        const today = new Date();
        const birthDate = new Date(dateString);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    };

    const makeContentEditable = (section) => {
        const paragraphs = section.querySelectorAll('p');
        paragraphs.forEach(p => {
            if (p.id === 'date_of_birth') {
                const title = p.textContent.split(': ')[0] + ': ';
                const value = p.textContent.split(': ')[1] || '';
                const dateInput = document.createElement('input');
                dateInput.type = 'date';
                dateInput.min = '2010-01-01';
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                dateInput.max = `${year}-${month}-${day}`;
                
                dateInput.value = value;
                dateInput.className = 'edit-input';
                dateInput.style.width = '100%';
                p.innerHTML = '';
                p.textContent = title;
                p.appendChild(dateInput);
            } else if (p.id !== 'age' && p.id !== 'full_name') {
                const value = p.textContent.trim();
                const textarea = document.createElement('textarea');
                textarea.value = value;
                textarea.className = 'edit-input';
                textarea.style.width = '100%';
                textarea.style.height = '100px';
                p.innerHTML = '';
                p.appendChild(textarea);
            }
        });
    };
    
    const saveContent = async (section) => {
        const inputs = section.querySelectorAll('.edit-input');
        const data = {};
        let newDateOfBirth = null;
        inputs.forEach(input => {
            const p = input.parentElement;
            const key = p.id;
            const newValue = input.value.trim();
            if (key === 'date_of_birth') {
                const title = p.textContent.split(': ')[0] + ': ';
                p.textContent = `${title}${newValue}`;
                newDateOfBirth = newValue;
            } else if (key === 'full_name') {
                const title = p.textContent.split(': ')[0] + ': ';
                p.textContent = `${title}${newValue}`;
            } else {
                p.textContent = newValue;
            }
            data[key] = newValue;
        });

        if (newDateOfBirth) {
            const age = calculateAge(newDateOfBirth);
            const ageParagraph = section.querySelector('#age');
            if (ageParagraph) {
                ageParagraph.textContent = `Age: ${age}`;
                data.age = age;
            }
        }

        try {
            const response = await fetch('/updateMedicalInfo', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            console.log(response);
            if (!result.success) {
                console.error('Error updating medical information:', result.error);
            }
        } catch (error) {
            console.error('Error updating medical information:', error);
        }

        // Print data in a human-readable format
        console.log('Data:', JSON.stringify(data, null, 2));
    };

    const updateField = (fieldId, value) => {
        console.log(`UpdateField called with Field ID: ${fieldId}, Value: ${value}`);
        if (value !== undefined && value !== null) {
            const element = document.getElementById(fieldId);
            if (element) {
                if (value !== "") {
                    console.log(`Updating element with ID ${fieldId} to value: ${value}`);
                    element.textContent = value;
                }
                else{
                    console.log('Value stays default');
                }
            } else {
                console.warn(`Element with ID ${fieldId} not found.`);
            }
        } else {
            console.warn(`Value for field ${fieldId} is undefined or null.`);
        }
    };


    try {
        const response = await fetch('/getMedicalInfo');
        if (!response.ok) {
            throw new Error('Failed to fetch medical information');
        }
        const medicalInfo = await response.json();
        console.log('Data:', JSON.stringify(medicalInfo, null, 2));

        updateField('emergency', medicalInfo[0].emergency_contact_info);
        updateField('med_conditions', medicalInfo[0].medical_conditions);
        updateField('medication', medicalInfo[0].medication);
        updateField('allergies', medicalInfo[0].allergies);
        updateField('immunization', medicalInfo[0].immunization_record);
        updateField('insurance', medicalInfo[0].insurance_info);
        updateField('history', medicalInfo[0].medical_history);
    } catch (error) {
        console.error('Error fetching medical information:', error);
    }


    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const section = button.parentElement;
            if (button.textContent === 'Edit') {
                makeContentEditable(section);
                button.textContent = 'Save';
            } else {
                saveContent(section);
                button.textContent = 'Edit';
            }
        });
    });
});
