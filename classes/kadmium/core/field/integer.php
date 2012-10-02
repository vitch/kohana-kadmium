<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_Integer extends Jelly_Core_Field_Integer
{

	public function set($value)
	{
		if ($this->allow_null && ($value == '' || $value == NULL)) {
			return '';
		}
		return parent::set($value);
	}
}
