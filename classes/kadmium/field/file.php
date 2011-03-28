<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_File extends Jelly_Field_File
{
	public $web_path = '';

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

	public function get_web_path($path, $thumbnail_index = -1)
	{
		return $this->web_path ? $this->web_path : str_replace(DOCROOT, '', $path);
	}
}