<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasMany extends Jelly_Core_Field_HasMany
{
	var $show_in_list = FALSE;

	public function input($prefix = 'jelly/field', $data = array())
	{
		$data['ids'] = array();
		foreach ($data['value'] as $model)
		{
			$data['ids'][] = $model->id();
		}
		// Could use
		// $data['ids'] = $data['value']->as_array(null, 'id');
		// but how to generalise it so that "id" isn't hard coded?

		return parent::input($prefix, $data);
	}
}