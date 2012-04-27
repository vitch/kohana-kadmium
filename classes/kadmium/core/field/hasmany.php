<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasMany extends Jelly_Core_Field_HasMany
{
	var $show_in_list = FALSE;

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
		$items = $value->select();
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