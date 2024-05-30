<div class="form-container">
	<h2>Change user info</h2>
	<div style="padding: 20px;">
		<form id="passwordForm">
			<label for="oldPassword">Old password:</label>
			<input class="edit" type="password" id="oldPassword" name="oldPassword" required>
			<label for="password">New password:</label>
			<input class="edit" type="password" id="newPassword" name="newPassword" required>
			<label for="confirmPassword">Confirm password:</label>
			<input class="edit" type="password" id="confirmPassword" name="confirmPassword" required>
			<input type="submit" value="Confirm">
		</form>
	</div>
	<div class="container" id="message" style="display: none"></div>
</div>

<script>
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const oldPassword = document.getElementById('oldPassword').value;
		const newPassword = document.getElementById('newPassword').value;
		const confirmPassword = document.getElementById('confirmPassword').value;

        const data = {
            oldPassword: oldPassword,
			newPassword: newPassword,
			confirmPassword: confirmPassword
        };

        async function updatePassword() {
            const messageDiv = document.getElementById('message');
            try {
                const response = await fetch('/api/users/password', {
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
                    messageDiv.innerHTML = 'Password updated successfully';
                } else {
                    messageDiv.innerHTML = 'Error: ' + responseData.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = 'An error occurred while updating the password';
            }
            messageDiv.style.display = 'block';
        }

        updatePassword();
    });
</script>