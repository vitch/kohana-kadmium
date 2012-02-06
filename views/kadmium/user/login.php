<h1>Login</h1>
<p>Please enter you details on the form below to log in to the administration area of the website.</p>
<?php
if ($feedback_message != '') {
?>
<div class="alert alert-error">
	<p><?= $feedback_message; ?></p>
</div>
<?php
}

echo View::factory(
	'kadmium/user/login_form',
	array(
		'username' => $username,
		'redirect' => Kohana::$config->load('kadmium')->base_path
	)
);

?>
