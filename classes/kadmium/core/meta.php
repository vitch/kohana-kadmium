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

}