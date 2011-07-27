<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles boolean data types.
 *
 * @package  Kadmium
 */
abstract class Kadmium_Field_Boolean extends Jelly_Core_Field_Boolean
{

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
}
