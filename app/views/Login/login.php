<?php
if (isset($_GET['message'])) {
    echo '<div class="container"><p>'
     . htmlspecialchars($_GET['message']) . '</p></div>';
}
?>

<form action="/loginTest" method="post">
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