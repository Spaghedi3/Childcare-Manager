document.addEventListener('DOMContentLoaded', async () => {
    const editButtons = document.querySelectorAll('.relationship button');

    const makeContentEditable = (section) => {
        const paragraphs = section.querySelectorAll('p');
        paragraphs.forEach(p => {
            if (p.classList.contains('name') || p.classList.contains('contact')) {
                const title = p.textContent.split(': ')[0] + ': ';
                const value = p.textContent.split(': ')[1] || '';
                const textarea = document.createElement('textarea');
                textarea.value = value;
                textarea.className = 'edit-input';
                textarea.style.width = '100%';
                textarea.style.height = '100px';
                p.innerHTML = ''; 
                p.textContent = title; 
                p.appendChild(textarea); 
            }
        });
    };

    const saveContent = async (section) => {
        const inputs = section.querySelectorAll('.edit-input');
        const data = {};

        const relationshipType = section.id; 
        data['relationship_type'] = relationshipType;

        inputs.forEach(input => {
            const p = input.parentElement;
            const key = p.classList.contains('name') ? 'name' : 'contact';
            const newValue = input.value.trim();
            p.textContent = `${p.textContent.split(': ')[0]}: ${newValue}`;
            data[key] = newValue;
        });

        try {
            const response = await fetch('/api/updateRelationship', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            console.log(response);
            if (!result.success) {
                console.error('Error updating relationship information:', result.error);
            }
        } catch (error) {
            console.error('Error updating relationship information:', error);
        }

        console.log('Data:', JSON.stringify(data, null, 2));
    };

    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            event.stopPropagation();
            const section = button.closest('.relationship'); 
            if (!section) {
                console.error('Parent section not found for button');
                return;
            }

            if (button.textContent === 'Edit') {
                makeContentEditable(section);
                button.textContent = 'Save';
            } else {
                saveContent(section);
                button.textContent = 'Edit';
            }
        });
    });

    try {
        const response = await fetch('/api/getRelationships');
        if (!response.ok) {
            throw new Error('Failed to fetch relationships information');
        }
        const relationshipInfo = await response.json();
        console.log('Data:', JSON.stringify(relationshipInfo, null, 2));
        updateFields(relationshipInfo); 
    } catch (error) {
        console.error('Error fetching relationships information:', error);
    }
});

function updateFields(relationshipInfo) {
    try {
        relationshipInfo.forEach(info => {
            const relationshipType = info.relationship_type;
            const name = info.name;
            const contactInfo = info.contact_info;

            const section = document.getElementById(relationshipType); 
            if (!section) {
                console.error(`Section not found for relationship type: ${relationshipType}`);
                return;
            }

            const nameParagraph = section.querySelector('.name');
            const contactParagraph = section.querySelector('.contact');

            if (nameParagraph) {
                nameParagraph.textContent = `Names: ${name}`;
            } else {
                console.error(`Name paragraph not found for relationship type: ${relationshipType}`);
            }

            if (contactParagraph) {
                contactParagraph.textContent = `Contact Information: ${contactInfo}`;
            } else {
                console.error(`Contact paragraph not found for relationship type: ${relationshipType}`);
            }
        });
    } catch (error) {
        console.error('Error updating fields:', error);
    }
}
