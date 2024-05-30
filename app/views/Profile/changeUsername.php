<div class="form-container">
    <h2>Change user info</h2>
    <div style="padding: 20px;">
        <form id="usernameForm">
            <label for="username">New username:</label>
            <input class="edit" type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            <input type="submit" value="Confirm">
        </form>
    </div>
    <div class="container" id="message" style="display: none"></div>
</div>

<script>
    document.getElementById('usernameForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const username = document.getElementById('username').value;

        const data = {
            username: username
        };

        async function updateUsername() {
            const messageDiv = document.getElementById('message');
            try {
                const response = await fetch('/api/users/username', {
                    method: 'PUT',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const responseData = await response.json();

                if (responseData.status === 'success') {
                    const userValue = document.getElementById('userValue');
                    userValue.innerHTML = username;
                    messageDiv.innerHTML = 'Username updated successfully';
                } else {
                    messageDiv.innerHTML = 'Error: ' + responseData.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = 'An error occurred while updating the username';
            }
            messageDiv.style.display = 'block';
        }

        updateUsername();
    });
</script>