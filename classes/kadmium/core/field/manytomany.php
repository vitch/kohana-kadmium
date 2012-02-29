<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_ManyToMany extends Jelly_Core_Field_ManyToMany
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
		$attrs['class'] = Arr::get($attrs, 'class', '') . ' span7 manytomany';
		$attrs['title'] = 'Select ' . $this->label . '...';
		if (isset($this->sort_on) && $this->sort_on) {
			$attrs['data-sortable'] = 1;
		}
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
	 * Overridden to delete any records from the join table.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed        $key
	 * @return  void
	 */
	public function delete($model, $key)
	{
		Jelly::query($this->through['model'])
				->where($this->through['model'] . '.' . $this->through['fields'][0], '=', $model->id())
				->delete();
		return parent::delete($model, $key);
	}

	public function save($model, $value, $loaded)
	{
		parent::save($model, $value, $loaded);
		if (isset($this->sort_on) && $value) {
			$i = 1;
			foreach($value as $linked_id) {
				if (!$linked_id) {
					continue;
				}
				$link_model = Jelly::query($this->through['model'])
					->where($this->through['fields'][0], '=', $model->id())
					->where($this->through['fields'][1], '=', $linked_id)
					->limit(1)
					->select();
				$link_model->set($this->sort_on, $i++)->save();
			}
		}
	}

	// Over-ridden so that if value includes commas we automatically explode it into an array - a bit hacky but should be OK?!
	public function set($value)
	{
		if (is_string($value) && strpos($value, ',') !== FALSE) {
			$value = explode(',', $value);
		}
		return parent::set($value);
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
		if ($model->changed($this->name) && $value) {
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