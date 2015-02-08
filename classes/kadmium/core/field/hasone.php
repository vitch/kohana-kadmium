<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasOne extends Jelly_Core_Field_HasOne
{

	public function display($model, $value)
	{
		return $value->select()->{Jelly::meta($this->foreign['model'])->name_key()};
	}
}