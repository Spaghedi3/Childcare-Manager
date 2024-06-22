<div class="form-container">
    <h2>Change user info</h2>
    <div style="padding: 20px;">
        <form id="emailForm">
            <label for="email">New email:</label>
            <input class="edit" type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <input type="submit" value="Confirm">
        </form>
    </div>
    <div class="container" id="message" style="display: none"></div>
</div>

<script>
    document.getElementById('emailForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const email = document.getElementById('email').value;

        const data = {
            email: email
        };

        async function updateEmail() {
            const messageDiv = document.getElementById('message');
            try {
                const response = await fetch('/api/users', {
                    method: 'PATCH',
                    credentials: 'include',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const responseData = await response.json();

                if (responseData.status === 'success') {
                    const emailValue = document.getElementById('emailValue');
                    emailValue.innerHTML = email;
                    messageDiv.innerHTML = 'Email updated successfully';
                } else {
                    messageDiv.innerHTML = 'Error: ' + responseData.message;
                }
            } catch (error) {
                console.error('Error:', error);
                messageDiv.innerHTML = 'An error occurred while updating the email';
            }
            messageDiv.style.display = 'block';
        }

        updateEmail();
    });
</script>