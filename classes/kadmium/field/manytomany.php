<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_ManyToMany extends Jelly_Field_ManyToMany
{

	var $show_in_list = FALSE;

	/**
	 * Adds a title to the attributes array to work nicely with jquery-asmselect
	 *
	 * @param string $prefix
	 * @param array $data
	 * @return View
	 */
	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$attrs['title'] = 'Select ' . $this->label . '...';
		$data['attributes'] = $attrs;
		return parent::input($prefix, $data);
	}

	/**
	 * Overridden the behaviour in Jelly to load the result via a model and
	 * respect aliases on that model for column names...
	 */
	protected function _in($model, $as_array = FALSE)
	{
		$result = Jelly::select($this->through['model'])
				->columns(array($this->through['columns'][1]))
				->where($this->through['columns'][0], '=', $model->id());

		if ($as_array)
		{
			$result = $result
						->execute(Jelly::meta($model)->db())
						->as_array(NULL, $this->through['columns'][1]);
		}

		return $result;
	}
}