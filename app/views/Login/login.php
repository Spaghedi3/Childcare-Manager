    <form action="#" method="post">
        <div class="imgcontainer"></div>
        <div class="container">
            <h1 id="login">Log into your account</h1>
            <label for="uname"><b>Username</b></label>
            <input class="login" type="text" placeholder="Enter Username" id="uname" name="uname" required>
            <br>
            <label for="psw"><b>Password</b></label>
            <input class="login" type="password" placeholder="Enter Password" id="psw" name="psw" required>
            <br>
            <input type="submit" value="Login">
            <br>
            <label>
                <input type="checkbox" checked="checked" name="remember"> Remember me
            </label>
        </div>

        <div class="container">
            <span class="psw"> <a href="#">Forgot password?</a></span>
            <br>
            <a href="/register">Don't have an account?</a>
        </div>
    </form>
