<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_HasManyUniquely extends Jelly_Field_HasMany
{

	public $edit_link_base = '';

	public function input($prefix = 'jelly/field', $data = array())
	{
		/*
		$data['ids'] = array();
		// Grab the IDS
		foreach ($data['value'] as $model)
		{
			$data['ids'][] = $model->id();
		}
		*/
		return parent::input($prefix, $data);
	}
}