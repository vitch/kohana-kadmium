<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_HasManyUniquely extends Jelly_Field_HasMany
{

	public $show_in_list = FALSE;
	// Default to not warning when deleting
	public $ignore_for_delete = TRUE;
	// And automatically deleting the associated children (since they can't be uniquely associated with anything else
	// at a later date anyway)
	public $delete_dependent = TRUE;

	public function input($prefix = 'jelly/field', $data = array())
	{
		if (Arr::get($data, 'add_link_view') == '') {
			$data['add_link_view'] = $prefix . '/hasmanyuniquely/add_link';
		}
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