<?php
echo Form::file($name, $attributes + array('id' => 'field-'.$name));
if ($value != '' && !is_array($model->{$field->name})) {
	echo '<label>Current file:</label>';
	echo '<div style="clear: left">';
	echo Html::anchor(
		$field->get_web_path($field->path) . $model->{$field->name},
		$model->{$field->name},
		array(
			'target' => '_blank'
		)
	);
	echo '</div>';
}
?>