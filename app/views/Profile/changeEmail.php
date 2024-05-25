<div class="form-container">
    <h2>Change user info</h2>
    <div style="padding: 20px;">
        <form action="/profileEdit" method="POST">
            <label for="email">New email:</label>
            <input class="edit" type="text" id="email" name="email" value="<?php echo $email; ?>">
            <input type="submit" value="Confirm">
        </form>
    </div>
</div>