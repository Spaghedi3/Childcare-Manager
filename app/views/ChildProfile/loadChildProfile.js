document.addEventListener('DOMContentLoaded', () => {
    const fetchChildProfile = async () => {
        try {
            const response = await fetch(`/getChildProfile`, { 
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