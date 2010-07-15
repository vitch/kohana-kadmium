<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles long strings with WYSIWYG editing (adds a css class to the
 * HTML textarea so you can easily hook in e.g. TinyMCE)
 *
 * @package  Jelly
 */
abstract class Kadmium_Field_RichText extends Field_Text
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
}
