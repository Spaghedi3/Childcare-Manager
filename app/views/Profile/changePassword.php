<div class="form-container">
	<h2>Change user info</h2>
	<div style="padding: 20px;">
		<form action="/profileEdit" method="POST">
			<label for="oldPassword">Old password:</label>
			<input class="edit" type="password" id="oldPassword" name="oldPassword">
			<label for="password">New password:</label>
			<input class="edit" type="password" id="password" name="password">
			<label for="confirmPassword">Confirm password:</label>
			<input class="edit" type="password" id="confirmPassword" name="confirmPassword">
			<input type="submit" value="Confirm">
		</form>
	</div>
</div>