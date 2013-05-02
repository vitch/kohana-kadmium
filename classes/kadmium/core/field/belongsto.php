<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_BelongsTo extends Jelly_Core_Field_BelongsTo
{
	
	public $edit_inline = false;
	public $autocomplete = false;

	public function input($prefix = 'jelly/field', $data = array())
	{
		if ($this->autocomplete) {
			$attrs = Arr::get($data, 'attributes', array());
			$attrs['class'] = Arr::get($attrs, 'class') . ' js-single-autocomplete';
			$attrs['data-match-contains'] = $this->match_contains ? '1' : '0';
			$attrs['data-sortable'] = isset($this->sort_on) ? '1' : '0';
			$data['attributes'] = $attrs;
		}
		return parent::input($prefix, $data);
	}

	public function display($model, $value)
	{
		if ($value instanceof Jelly_Builder) {
			$value = $value->select();
		}
		return $value->name();
	}
}