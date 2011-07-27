<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_BelongsTo extends Jelly_Core_Field_BelongsTo
{
	
	public $edit_inline = false;

	public function display($model, $value)
	{
		if ($value instanceof Jelly_Builder) {
			$value = $value->execute();
		}
		return $value->name();
	}
}