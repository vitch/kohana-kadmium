<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_File extends Jelly_Field_File
{

	/**
	 * Adds the CSS class I want all of my textfields to have.
	 *
	 * @param   string  $model
	 * @param   string  $column
	 * @return  void
	 **/
	public function initialize($model, $column)
	{
		parent::initialize($model, $column);
		array_push($this->css_class, 'inp-text');
	}
}