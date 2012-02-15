<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Handles items that are sortable...
 *
 * @package  Jelly
 */
abstract class Kadmium_Core_Field_SortOn extends Jelly_Field_Integer
{
	public $show_in_edit = false;
	public $append_at_end = true;
	public $category_key;
	public $category_dir = 'ASC';
	public $label = 'Sort';

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
			if ($this->append_at_end) {
				$builder = Jelly::query($model->meta()->model());
				if (isset($this->category_key)) {
					$builder->where($this->category_key, '=', $model->get_raw($this->category_key));
				}
				$value = $builder->count() + 1;
			} else {
				$builder = Jelly::query($model->meta()->model());
				if (isset($this->category_key)) {
					$builder->where($this->category_key, '=', $model->get_raw($this->category_key));
				}
				$builder->set(
					array(
						$this->column => Db::expr($this->column . '+1')
					)
				)->update();
				$value = 1;
			}
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
