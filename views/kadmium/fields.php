<?php
	foreach ($fields as $label => $field) {
		$wrapper_class = 'clearfix';
		if (isset($field->li_class)) { // TODO: What is this used for again?
			$wrapper_class .= ' ' . $field->li_class;
		}
		if (isset($field->errors)) {
			$wrapper_class .= ' error';
		}
?>
	<div class="<?= $wrapper_class; ?>">
		<?php
			echo $label;
			echo '<div class="input">';
			echo $field;
			if (isset($field->errors)) {
		?>
				<div class="alert-message error">
					<?= $field->errors; ?>
				</div>
		<?php
			}
			echo '</div>';
		?>
	</div>
<?php
	}
?>