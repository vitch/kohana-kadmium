<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_Timestamp extends Jelly_Core_Field_Timestamp
{

	var $pretty_format = 'Y-m-d';
	var $format = 'Y-m-d';

	/**
	 * Adds the CSS class I want all of my textfields to have.
	 *
	 * @param   string  $model
	 * @param   string  $column
	 * @return  void
	 **/
	public function initialize($model, $column)
	{
		parent::initialize($model, $column);
		array_push($this->css_class, 'span2');
	}

	public function save($model, $value, $loaded)
	{
		if ($value == '' && $this->allow_null) {
			return NULL;
		}
		return parent::save($model, $value, $loaded);
	}

	/**
	 * Returns a particular value processed according
	 * to the class's standards.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed        $value
	 * @return  string
	 **/
	public function display($model, $value)
	{
		return date($this->pretty_format, strtotime($value));
	}
}