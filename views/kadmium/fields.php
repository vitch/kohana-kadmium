	<?php
		foreach ($fields as $label => $field):
	?>
	<li>
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