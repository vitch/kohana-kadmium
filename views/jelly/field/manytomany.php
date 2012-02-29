<?php
	echo '<select ' .
		HTML::attributes(
			$attributes +
			array(
				'id' => 'field-'.$name,
				'name' => $name . '[]',
				'multiple' => 'multiple'
			)
		) .
		'>';
	foreach(Jelly::query($foreign['model'])->select() as $related) {
		$option_attributes = array(
			'value' => $related->id(),
		);
		$pos = array_search($related->id(), $ids);
		if ($pos !== FALSE) {
			$option_attributes['selected'] = 'selected';
			$option_attributes['data-pos'] = $pos;
		}
		echo '<option ' . Html::attributes($option_attributes) . '>' . $related->name() . '</option>';
	}
	echo '</select>';
?>