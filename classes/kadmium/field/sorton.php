<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles items that are sortable...
 *
 * @package  Jelly
 */
abstract class Kadmium_Field_SortOn extends Jelly_Field_Integer
{
	public $show_in_edit = false;
	public $category_key;

	/**
	 * If value is null (e.g. initial saving of model) then value
	 * is set to the number of records in this column.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed  $value
	 * @return  mixed
	 */
	public function save($model, $value, $loaded)
	{
		if ($value == null) {
			$builder = Jelly::select($model->meta()->model());
			if (isset($this->category_key)) {
				// TODO: There must be a way to just get at the value without having to execute the
				// query and then get it back out?!??!
				$foreign = $model->get($this->category_key)->execute();
				$builder->where($this->category_key, '=', $foreign->get($foreign->meta()->primary_key()));
			}
			$value = $builder->count() + 1;
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
