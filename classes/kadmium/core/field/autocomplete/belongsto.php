<?php defined('SYSPATH') or die('No direct script access.');

class Kadmium_Core_Field_Autocomplete_BelongsTo extends Jelly_Field_BelongsTo
{

	public $uses_autocomplete = true;
	public $match_contains = true;

	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$attrs['class'] = Arr::get($attrs, 'class') . ' js-autocomplete';
		$attrs['data-match-contains'] = $this->match_contains ? '1' : '0';
		$attrs['data-sortable'] = isset($this->sort_on) ? '1' : '0';
		$attrs['data-one-choice'] = 1;
		$data['attributes'] = $attrs;
		return parent::input($prefix, $data);
	}
	
}