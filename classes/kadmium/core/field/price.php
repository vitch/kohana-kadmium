<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_Price extends Jelly_Field_Integer
{

	public $currency_symbol = '£';

	public function set($value)
	{
		if ($this->allow_null AND $value === NULL)
		{
			return NULL;
		}
		$value = round(floatval($value) * 100);

		return (int)$value;
	}

	public function get($model, $value)
	{
		return number_format($value/10000, 2, '.', ''); /* a bit dodgy - seem to need to devide by 100*100! */
	}

	public function display($model, $value)
	{
		return $this->currency_symbol . $value;
	}
}
