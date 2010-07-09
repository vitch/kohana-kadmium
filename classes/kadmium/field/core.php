<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Core extends Jelly_Field_Core
{

	/**
	* @var  array  Any CSS classes you want to add to this fields input() element.
	*/
	public $css_class = array();

	/**
	* @var  boolean  Whether this field should be rendered in a list view.
	*/
	public $show_in_list = true;

	/**
	* @var  boolean  Whether this field should be rendered in an edit view.
	*/
	public $show_in_edit = true;


	/**
	 * Displays the particular field as a form item
	 *
	 * @param string $prefix The prefix to put before the filename to be rendered
	 * @return View
	 **/
	public function input($prefix = 'jelly/field', $data = array())
	{
		// Make sure there is an 'attrs' array set to prevent error in view
		$attrs = Arr::get($data, 'attributes', array());

		// And push in any classes specified in the css_class variable
		if (count($this->css_class)) {
			$css_class_attr = Arr::get($attrs, 'class', '');
			if ($css_class_attr != '') {
				$css_class_attr .= ' ';
			}
			$attrs['class'] = $css_class_attr . implode(' ', $this->css_class);
		}
		$data['attributes'] = $attrs;

		return parent::input($prefix, $data);
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
		return $value.'';
	}
}
