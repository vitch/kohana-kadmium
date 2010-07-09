<?php defined('SYSPATH') or die('No direct script access.');

class Kadmium_Field_BelongsTo extends Jelly_Field_BelongsTo
{

	public function display($model, $value)
	{
		return $value->execute()->{Jelly::meta($this->foreign['model'])->name_key()};
	}
}