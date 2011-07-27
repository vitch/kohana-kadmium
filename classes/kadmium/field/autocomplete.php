<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Autocomplete extends Field_ManyToMany
{

	public $match_contains = false;

	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$attrs['class'] = Arr::get($attrs, 'class') . ' js-autocomplete';
		$attrs['data-match-contains'] = $this->match_contains ? '1' : '0';
		$attrs['data-sortable'] = isset($this->sort_on) ? '1' : '0';
		$data['attributes'] = $attrs;
		return parent::input($prefix, $data);
	}

	public function set($value)
	{
		if (is_string($value)) {
			return $this->_ids(explode(',', $value));
		} else {
			return parent::set($value);
		}
	}

	public function get($model, $value)
	{
		if (is_array($value) && count($value) == 0) {
			return array();
		}
		return parent::get($model, $value);
	}

	public function save($model, $value, $loaded)
	{
		parent::save($model, $value, $loaded);
		if (isset($this->sort_on)) {
			$i = 1;
			foreach($value as $linked_id) {
				if (!$linked_id) {
					continue;
				}
				$link_model = Jelly::select($this->through['model'])
									->where($this->through['columns'][0], '=', $model->id())
									->where($this->through['columns'][1], '=', $linked_id)
									->limit(1)
									->execute();
				$link_model->set($this->sort_on, $i++)->save();
			}
		}
	}
}