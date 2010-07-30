<h1>Access denied</h1>
<div class="error">
	<p>Sorry, you don't have permission to view that page.</p>
</div>
<p>Please use the form below to log in:</p>
<?php
	echo View::factory(
		'kadmium/user/login_form',
		array(
			'username' => '',
			'redirect' => $_SERVER['REQUEST_URI']
		)
	);
?>
