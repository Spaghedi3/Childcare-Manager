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
    // TODO fetch api?
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var username = document.getElementById('username').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;
        var confirm_password = document.getElementById('confirm_password').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/api/users', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    window.location.href = '/login?message=Registered successfully, Please login!';
                } else {
                    var messageDiv = document.getElementById('message');
                    messageDiv.style.display = 'block';
                    messageDiv.innerText = response.message;
                }
            }
        };
        xhr.send(JSON.stringify({
            username: username,
            email: email,
            password: password,
            confirmPassword: confirm_password
        }));
    });
</script>