<?= Form::open('user/login'); ?>

	<input type="hidden" name="my-action" value="login" />
	<input type="hidden" name="next" value="<?= $redirect; ?>" />
	<fieldset>
		<div class="clearfix">
			<?php
				echo Form::label('username', 'Username');
				echo '<div class="input">';
				echo Form::input(
					'username',
					$username,
					array(
						'id' => 'username',
					)
				);
				echo '</div>';
			?>
		</div>
		<div class="clearfix">
			<?php
				echo Form::label('password', 'Password');
				echo '<div class="input">';
				echo Form::password(
					'password',
					'',
					array(
						'id' => 'password',
					)
				);
				echo '</div>';
			?>
		</div>
	</fieldset>
	<div class="actions">
	<?=
		Form::submit(
			'',
			'Log in',
			array(
				'class' => 'btn btn-large btn-primary',
			)
		);
	?>
	</div>
<?= Form::close(); ?>