<div class="row">
	<div class="span12">

		<div class="page-header">
			<h1>Login</h1>
		</div>
		<p>Please enter you details on the form below to log in to the administration area of the website.</p>
		<?php
		if ($feedback_message != '') {
		?>
		<div class="alert alert-error">
			<?= $feedback_message; ?>
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
	</div>
</div>