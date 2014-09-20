<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasMany extends Jelly_Core_Field_HasMany
{
	var $show_in_list = FALSE;

	public function input($prefix = 'jelly/field', $data = array())
	{
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
	 * Gets a string representation of the value, formatted according to the
	 * fields type.
	 *
	 * @param   Jelly_Model  $model
	 * @param   mixed        $value
	 * @return String
	 **/
	public function display($model, $value)
	{
		$items = $value->select(Jelly::meta($this->foreign['model'])->db());
		if (count($items)) {
			return View::factory(
				'kadmium/element/list_table',
				array(
					'items' => $items,
					'show_edit' => FALSE,
					'extra_button_view' => '',
				)
			);
		}
		return '&nbsp;';
	}
}