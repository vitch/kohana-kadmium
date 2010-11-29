<?php defined('SYSPATH') or die('No direct script access.');

class Kadmium_Field_Options extends Kadmium_Field_Text
{
	public function input($prefix = 'jelly/field', $data = array())
	{
		$data['options'] = $this->options;
		return parent::input($prefix, $data);
	}
}
