<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_String extends Jelly_Field
{

	public function input($prefix = 'jelly/field', $data = array())
	{
		$attrs = Arr::get($data, 'attributes', array());
		$css_class = Arr::get($attrs, 'class');
		$css_class .= ($css_class == '' ? '' : ' ') . 'xxlarge';
		$attrs['class'] = $css_class;
		$data['attributes'] = $attrs;
		return parent::input($prefix, $data);
	}
}
