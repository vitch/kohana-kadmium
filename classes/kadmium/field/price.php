<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Price extends Kadmium_Field_Integer
{

	public function set($value)
	{
		if ($value === NULL OR ($this->null AND empty($value)))
		{
			return NULL;
		}
		$value = round($value * 100);

		return (int)$value;
	}

	public function get($model, $value)
	{
		return number_format($value/10000, 2); /* a bit dodgy - seem to need to devide by 100*100! */
	}

	public function display($model, $value)
	{
		return $value . '';
	}
}
