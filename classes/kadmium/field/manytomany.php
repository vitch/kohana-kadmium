<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_ManyToMany extends Jelly_Field_ManyToMany
{
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
		return parent::input($prefix, $data);
	}
}