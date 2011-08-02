<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasManyUniquely extends Jelly_Field_HasMany
{

	var $show_in_list = FALSE;

	public function input($prefix = 'jelly/field', $data = array())
	{
		if (Arr::get($data, 'add_link_view') == '') {
			$data['add_link_view'] = $prefix . '/hasmanyuniquely/add_link';
		}
		return parent::input($prefix, $data);
	}

	// FIXME: Is this necessary or does Jelly_Core_Field_HasMany::delete and
	// Jelly_Core_Field_HasMany::$delete_dependent avoid the need for it?
	public function delete($model, $key)
	{
		if ($model->delete_policy == Kadmium_Core_Model::DELETE_ALL_CHILDREN) {
			$items = $model->get($this->name, FALSE)->select(); // Can we just use ->delete now?
			foreach($items as $item) {
				$item->delete();
			}
		}
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
		return View::factory(
			'kadmium/element/list_table',
			array(
				'items' => $value->select(),
				'show_edit' => FALSE,
				'extra_button_view' => '',
			)
		);
	}
}