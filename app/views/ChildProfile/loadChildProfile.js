document.addEventListener('DOMContentLoaded', () => {
    const getCookie = (name) => {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    };

    const urlParams = new URLSearchParams(window.location.search);
    let childId = urlParams.get('id');

    if (!childId) {
        childId = getCookie('childId');
    }

    const fetchChildProfile = async (id) => {
        try {
            const response = await fetch(`/getChildProfile?id=${id}`, { 
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            const profile = await response.json();
            document.getElementById('child-name').textContent = `Welcome to ${profile.name}'s Profile`;
            document.getElementById('child-image').src = profile.profile_picture_path;
        } catch (error) {
            console.error('Error fetching child profile:', error);
        }
    };

    if (childId) {
        fetchChildProfile(childId);
    } else {
        console.error('Child ID not found in URL or cookie');
    }
});