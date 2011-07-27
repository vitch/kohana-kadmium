<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_ManyToMany extends Jelly_Core_Field_ManyToMany
{

	var $show_in_list = FALSE;

	/**
	 * Adds a title to the attributes array to work nicely with jquery-asmselect
	 *
	 * @param string $prefix
	 * @param array $data
	 * @return View
	 */
	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$attrs['title'] = 'Select ' . $this->label . '...';
		$data['attributes'] = $attrs;

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