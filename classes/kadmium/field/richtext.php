<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles long strings with WYSIWYG editing (adds a css class to the
 * HTML textarea so you can easily hook in e.g. TinyMCE)
 *
 * @package  Jelly
 */
abstract class Kadmium_Field_RichText extends Jelly_Field_Text
{

	/**
	 * Adds a CSS class so that you can easily hook up a WYSIWYG text editor
	 *
	 * @param   string  $model
	 * @param   string  $column
	 * @return  void
	 **/
	public function initialize($model, $column)
	{
		parent::initialize($model, $column);
		array_push($this->css_class, 'wysiwyg');
	}

	/**
	 * Gets a string representation of the value, formatted according to the
	 * fields type.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed        $value
	 * @return String
	 **/
	public function display($model, $value)
	{
		return $value.''; // RichText should allow HTML characters that have been inputted?
	}
}
