<?php defined('SYSPATH') or die('No direct script access.');

class Kadmium_Field_Options extends Kadmium_Field_Text
{
	public function input($prefix = 'jelly/field', $data = array())
	{
		$data['options'] = $this->options;
		return parent::input($prefix, $data);
	}

	/**
	 * Returns a particular value processed according
	 * to the class's standards.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed        $value
	 * @return  string
	 **/
	public function display($model, $value)
	{
		return $this->options[$value];
	}
}
