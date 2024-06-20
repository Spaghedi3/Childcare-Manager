<div class="container" id="message" style="display: none"></div>

<div class="container">
    <h1>Create Account</h1>
    <form id="registerForm">
        <label for="username">Username:</label>
        <input class="create-account" type="text" id="username" name="username" placeholder="Enter username" required>
        <br>

        <label for="email">Email:</label>
        <input class="create-account" type="email" id="email" name="email" placeholder="Enter email address" required>
        <br>

        <label for="password">Password:</label>
        <input class="create-account" type="password" id="password" name="password" placeholder="Type password" required minlength="8">
        <br>

        <label for="confirm_password">Confirm Password:</label>
        <input class="create-account" type="password" id="confirm_password" name="confirm_password" placeholder="Re-type password" required minlength="8">
        <br>

        <input type="submit" value="Create Account">
    </form>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        var username = document.getElementById('username').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var confirm_password = document.getElementById('confirm_password').value;

        try {
            let response = await fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: username,
                    email: email,
                    password: password,
                    confirmPassword: confirm_password
                })
            });

            let data = await response.json();

            if (data.status === 'success') {
                window.location.href = '/login?message=Registered successfully, Please login!';
            } else {
                var messageDiv = document.getElementById('message');
                messageDiv.style.display = 'block';
                messageDiv.innerText = data.message;
            }
        } catch (error) {
            console.error('Error:', error);
            var messageDiv = document.getElementById('message');
            messageDiv.style.display = 'block';
            messageDiv.innerText = 'An error occurred. Please try again later.';
        }
    });
</script>