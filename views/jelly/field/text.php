<?=
	Form::textarea($name, $value, $attributes + array(
		'id' => 'field-'.$name,
	));
?>