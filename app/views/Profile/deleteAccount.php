<div class="form-container">
    <h2>Delete account</h2>
    <div style="padding: 20px;">
        <form id="deleteAccountForm">
            <label for="password">Password:</label>
            <input class="edit" type="text" id="password" name="password">
            <p>Are you sure you want to delete your account?</p>
            <input type="submit" value="Confirm">
        </form>
    </div>
    <div class="container" id="message" style="display: none"></div>
</div>

<script>
    document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const password = document.getElementById('password').value;
        const data = {
            password: password
        };

        async function deleteAccount() {
            const messageDiv = document.getElementById('message');
            try {
                const response = await fetch('/api/users', {
                    method: 'DELETE',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const responseData = await response.json();

                if (responseData.status === 'success') {
                    window.location.href = '/logout';
                } else {
                    messageDiv.innerHTML = 'Error: ' + responseData.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = 'An error occurred while deleting the account';
            }
            messageDiv.style.display = 'block';
        }

        deleteAccount();
    });
</script>