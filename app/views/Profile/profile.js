document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('exportDataBtn').addEventListener('click', function () {
        fetch('/api/users', {
            method: 'GET',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                const jsonData = JSON.stringify(data, null, 2);

                // Create a blob with the JSON data
                const blob = new Blob([jsonData], {
                    type: 'application/json'
                });

                const link = document.createElement('a');
                link.download = 'user_data.json';
                link.href = window.URL.createObjectURL(blob);
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
            })
            .catch(error => console.error('Error fetching user data:', error));
    });

    document.getElementById('logoutBtn').addEventListener('click', function () {
        fetch('/api/session', {
            method: 'DELETE',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => {
                if (response.ok) {
                    window.location.href = '/home';
                } else {
                    console.error('Error logging out:', response);
                }
            })
            .catch(error => console.error('Error logging out:', error));
    });
});