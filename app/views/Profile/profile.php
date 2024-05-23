<div class="container main-container">

	<div class="profile-container">
		<a href="#">
			<img src="app/views/images/default-profile.png" alt="Profile Picture" style="width: 200px; height: 200px;">
		</a>

		<div class="info-container">
			<p>
				<span class="label">Username:</span>
				<span class="value"><?php echo $username; ?></span>
				<a href="/profile?form=changeUsername">Edit</a>
			</p>
			<p>
				<span class="label">Email:</span>
				<span class="value"><?php echo $email; ?></span>
				<a href="/profile?form=changeEmail">Edit</a>
			</p>
			<a class="btn" href="/profile?form=changePassword">Change Password</a>
		</div>

	</div>

	<div class="button-container">
		<a class="btn" href="/logout">Logout</a>
		<a class="btn" href="/profile?form=deleteAccount">Delete account</a>
		<a class="btn" href="#">Export data</a>
	</div>

	<?php
	if (isset($_GET['form'])) {
		$form = $_GET['form'];
		switch ($form) {
			case 'changeUsername':
				require_once '../app/views/Profile/changeUsername.php';
				break;
			case 'changeEmail':
				require_once '../app/views/Profile/changeEmail.php';
				break;
			case 'changePassword':
				require_once '../app/views/Profile/changePassword.php';
				break;
			case 'deleteAccount':
				require_once '../app/views/Profile/deleteAccount.php';
				break;
		}
	}
	?>
</div>