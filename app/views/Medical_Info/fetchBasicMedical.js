const fetchBasic = async () => {
    try {
        // Get childId from cookie
        const cookies = document.cookie.split(';').map(cookie => cookie.trim());
        let childId;
        cookies.forEach(cookie => {
            const parts = cookie.split('=');
            if (parts[0] === 'childId') {
                childId = parts[1];
            }
        });

        if (!childId) {
            console.error('Child ID not found in cookies.');
            return;
        }

        const response = await fetch(`/api/children/${childId}`, {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch child information.');
        }

        const data = await response.json();
        console.log(data);
        renderBasic(data);
    } catch (error) {
        console.error('Error fetching info:', error);
    }
};

const renderBasic = (basic_info) => {
    console.log("aaaaa");
    document.getElementById('full_name').textContent += basic_info.name;
    document.getElementById('age').textContent += basic_info.age;
    document.getElementById('date_of_birth').textContent += basic_info.birth_date;
    document.getElementById('welcome').textContent += basic_info.name;
    document.getElementById('welcome').textContent += "'s Medical Information Page. This page is intended to provide important medical details for ";
    document.getElementById('welcome').textContent += basic_info.name;
    document.getElementById('welcome').textContent += " to ensure their safety and well-being in various situations. ";
};

fetchBasic();