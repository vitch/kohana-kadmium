<?php
echo Form::file($name, $attributes + array('id' => 'field-'.$name));
if ($value != '' && !is_array($model->{$field->name})) {
	echo '<span class="help-block">Current file: ';
	echo Html::anchor(
		$field->get_web_path($field->path) . $model->{$field->name},
		$model->{$field->name},
		array(
			'target' => '_blank',
		)
	);
	echo '</span>';
}
?>