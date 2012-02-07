<?=
	Form::open(
		null,
		array(
			'class' => 'form-horizontal',
		)
	);
?>

	<input type="hidden" name="my-action" value="login" />
	<input type="hidden" name="next" value="<?= $redirect; ?>" />
	<fieldset>
		<div class="control-group">
			<?php
				echo Form::label('username', 'Username');
				echo '<div class="controls">';
				echo Form::input(
					'username',
					$username,
					array(
						'id' => 'username',
						'class' => 'span7',
					)
				);
				echo '</div>';
			?>
		</div>
		<div class="control-group">
			<?php
				echo Form::label('password', 'Password');
				echo '<div class="controls">';
				echo Form::password(
					'password',
					'',
					array(
						'id' => 'password',
						'class' => 'span7',
					)
				);
				echo '</div>';
			?>
		</div>
	</fieldset>
	<div class="form-actions">
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