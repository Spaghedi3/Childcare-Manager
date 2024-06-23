document.addEventListener('DOMContentLoaded', () => {
    const fetchChildProfile = async () => {
        try {
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            const id = urlParams.get('id')

            let fetchUrl = '/api/children/';
            if (id)
                fetchUrl += `${id}`;
            else
                fetchUrl += getCookie('childId');

            const response = await fetch(fetchUrl, {
                method: 'GET',
                credentials: 'include',
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
    fetchChildProfile();
});

const getCookie = (name) => {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(';').shift();
};