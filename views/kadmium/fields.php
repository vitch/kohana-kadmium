	<?php
		foreach ($fields as $label => $field):
	?>
	<li<?= isset($field->li_class) ? ' class="' . $field->li_class . '"' : ''; ?>>
		<?= $label; ?>
		<?= $field; ?>
		<?php
		if (isset($field->errors)):
		?>
		<div class="error-message">
			<?= $field->errors; ?>
		</div>
		<?php
		endif;
		?>
	</li>
	<?php
		endforeach
	?>