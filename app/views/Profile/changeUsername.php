<div class="form-container">
    <h2>Change user info</h2>
    <div style="padding: 20px;">
        <form action="/profileEdit" method="POST">
            <label for="username">New username:</label>
            <input class="edit" type="text" id="username" name="username" value="<?php echo $username; ?>">
            <input type="submit" value="Confirm">
        </form>
    </div>
</div>