<?php

	$options = array();
	$loaded_options = Jelly::query($foreign['model'])->select();
	foreach($loaded_options as $option) {
		$options[$option->id()] = $option->name();
	}

	if (isset($field->allow_nil)) { // FIXME: Need to get this from the field...
		$options = array($field->allow_nil) + $options;
	}
	$attributes['class'] = Arr::get($attributes, 'class') . ' span7';
	echo Form::select($name, $options, $value->id(), $attributes + array('id' => 'field-'.$name));
?>