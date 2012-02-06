<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles boolean data types.
 *
 * @package  Kadmium
 */
abstract class Kadmium_Core_Field_Boolean extends Jelly_Core_Field_Boolean
{

	public $label_true = 'Yes';
	public $label_false = 'No';

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
		return $value ? $this->label_true : $this->label_false;
	}

	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$css_class = Arr::get($attrs, 'class');
		$css_class .= ($css_class == '' ? '' : ' ') . 'span7';
		$attrs['class'] = $css_class;
		$data['attributes'] = $attrs;
		return parent::input($prefix, $data);
	}
}
