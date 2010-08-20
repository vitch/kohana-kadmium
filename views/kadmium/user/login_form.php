<?=
Form::open('user/login');
?>

	<input type="hidden" name="my-action" value="login" />
	<input type="hidden" name="next" value="<?= $redirect; ?>" />
	<ul class="fields">
		<li>
			<label for="username">Username</label>
			<input type="text" name="username" id="username" value="<?= $username; ?>" />
		</li>
		<li>
			<label for="password">Password</label>
			<input type="password" name="password" id="password" />
		</li>
	</ul>
	<input type="submit" value="Log in">
</form>