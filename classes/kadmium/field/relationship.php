<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Relationship extends Jelly_Field_Relationship
{

	public $ignore_for_delete = FALSE;

	/**
	 * Copied from Jelly_Field_Relationship but allows for null/zero values with an
	 * additional label if the field specifies it.
	 *
	 * @param   string  $prefix  The prefix to put before the filename to be rendered
	 * @return  View
	 **/
	public function input($prefix = 'jelly/field', $data = array())
	{
		if ( ! isset($data['options']))
		{

			$options = array();
			$loaded_options = Jelly::select($this->foreign['model'])->execute();
			foreach($loaded_options as $option) {
				$options[$option->id()] = $option->name();
			}

			$data['options'] = $options;
			if (isset($this->allow_nil)) {
				$data['options'] = array($this->allow_nil) + $data['options'];
			}
		}

		return parent::input($prefix, $data);
	}
}