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
			if (isset($field->prepend)) {
				echo '<div class="input-prepend">';
				echo '<span class="add-on">' . $field->prepend . '</span>';
				echo $field;
				echo '</div>';
			} else {
				echo $field;

			}
			if (isset($field->errors)) {
		?>
				<span class="help-inline">
					<?= $field->errors; ?>
				</span>
		<?php
			}
			echo '</div>';
		?>
	</div>
<?php
	}
?>