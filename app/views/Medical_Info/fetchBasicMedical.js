const fetchBasic = async () => {
    try {
        const response = await fetch('/api/getBasic', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        renderBasic(data);
    } catch (error) {
        console.error('Error fetching info:', error);
    }
};

const renderBasic = (basic_info) => {
    console.log("aaaaa");
    document.getElementById('full_name').textContent += basic_info[0].name;
    document.getElementById('age').textContent += basic_info[0].age;
    document.getElementById('date_of_birth').textContent += basic_info[0].birth_date;
    document.getElementById('welcome').textContent += basic_info[0].name;
    document.getElementById('welcome').textContent += "'s Medical Information Page. This page is intended to provide important medical details for ";
    document.getElementById('welcome').textContent += basic_info[0].name;
    document.getElementById('welcome').textContent += " to ensure their safety and well-being in various situations. ";
};

fetchBasic();