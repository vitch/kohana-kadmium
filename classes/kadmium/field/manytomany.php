<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_ManyToMany extends Jelly_Core_Field_ManyToMany
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

		$data['ids'] = array();
		foreach ($data['value'] as $model)
		{
			$data['ids'][] = $model->id();
		}
		// Could use
		// $data['ids'] = $data['value']->as_array(null, 'id');
		// but how to generalise it so that "id" isn't hard coded?
		
		return parent::input($prefix, $data);
	}

	/**
	 * Returns a Jelly_Builder that can be selected, updated, or deleted.
	 *
	 * @param   Jelly_Model    $model
	 * @param   mixed          $value
	 * @return  Jelly_Builder
	 */
	public function get($model, $value)
	{
		if (!isset($this->sort_on)) {
			return parent::get($model, $value);
		}
		if ($model->changed($this->name)) {
			// FIXME: This happens when e.g. validation errors prevent an item from being saved...
			// The order is lost in these cases - is this a problem?
			return parent::get($model, $value);
		}

		// Special code to deal with when we want to be able to sort on a field in the join table...
		$result = Jelly::query($this->foreign['model'])
						->join($this->through['model'])
						->on($this->through['model'] . '.' . $this->through['fields'][1], '=', $this->foreign['model'] . '.:primary_key')
						->where($this->through['model'] . '.' . $this->through['fields'][0], '=', $model->id())
						->order_by($this->through['model'] . '.' . $this->sort_on)
		               ->type(Database::SELECT);
		
		return $result;
	}

}