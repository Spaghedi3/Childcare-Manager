<?php
if (isset($_GET['message'])) {
    echo '<div class="container">'
     . htmlspecialchars($_GET['message']) . '</div>';
}
else
{
    echo '<div class="container" id="message" style="display: none"></div>';
}
?>

<form id="loginForm">
    <div class="imgcontainer"></div>
    <div class="container">
        <h1 id="login">Log into your account</h1>
        <label for="username"><b>Username</b></label>
        <input class="login" type="text" placeholder="Enter Username" id="username" name="username" required>
        <br>
        <label for="password"><b>Password</b></label>
        <input class="login" type="password" placeholder="Enter Password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
        <br>
        <label>
            <input type="checkbox" name="remember"> Remember me
        </label>
    </div>

    <div class="container">
        <a href="/register">Don't have an account?</a>
    </div>
</form>

<script>
    document.getElementById('loginForm').addEventListener('submit', async function(event) {
        event.preventDefault();
        var username = document.getElementById('username').value;
        var password = document.getElementById('password').value;

        try {
            let response = await fetch('/api/session?username=' + username + '&password=' + password, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            let data = await response.json();

            if (data.status === 'success') {
                window.location.href = '/select';
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