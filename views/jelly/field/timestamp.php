<?php
$val = strtotime($value);
if ($val !== FALSE) {
	$value = date($field->format, $val);
}
echo Form::input(
		$name,
		$value,
		$attributes + array('id' => 'field-'.$name, 'type' => 'date', 'data-datepicker' => 'datepicker')
	);
