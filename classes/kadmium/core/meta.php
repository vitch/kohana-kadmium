<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Meta extends Jelly_Core_Meta
{

	/**
	 * @var  string  Prefix to apply to input view generation
	 */
	protected $input_prefix = 'jelly/field';

	/**
	 * Gets or sets the object's input prefix
	 * @param   string  $value
	 * @return  string
	 */
	public function input_prefix($value = NULL)
	{
		if (func_num_args() !== 0)
		{
			return $this->set('input_prefix', $value);
		}

		return $this->input_prefix;
	}

	// Overridden to add automatic sorting of models containing a Jelly_Field_SortOn
	public function finalize($model)
	{
		if ($this->_initialized)
			return;

		if (count($this->sorting()) == 0) {
			foreach($this->fields() as $column => $field) {
				if ($field instanceof Jelly_Field_SortOn) {
					$this->sorting(
						array(
							$column => 'asc'
						)
					);
					break;
				}
			}
		}
		
		parent::finalize($model);
	}

}
