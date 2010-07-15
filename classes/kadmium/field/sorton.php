<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles long strings
 *
 * @package  Jelly
 */
abstract class Kadmium_Field_SortOn extends Field_Integer
{
	public $show_in_edit = false;

	/**
	 * If value is null (e.g. initial saving of model) then value
	 * is set to the number of records in this column.
	 *
	 * TODO: What if the item has categories...
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed  $value
	 * @return  mixed
	 */
	public function save($model, $value, $loaded)
	{
		if ($value == null) {
			$value = Jelly::select($model->meta()->model())->count() + 1;
		}
		return $value;
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
		return '<span class="sort-on" rel="' . $model->id() . '">' . $value . '</span>';
	}
}
