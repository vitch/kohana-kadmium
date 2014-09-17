<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field extends Jelly_Core_Field
{

	/**
	* @var  array  Any CSS classes you want to add to this fields input() element.
	*/
	public $css_class = array();

	/**
	* @var  boolean  Whether this field should be rendered in a list view.
	*/
	public $show_in_list = true;

	/**
	* @var  boolean  Whether this field should be rendered in an edit view.
	*/
	public $show_in_edit = true;

	/**
	 * @var boolean Whether this field should be non-editable on edit pages (e.g. shown but not as a form element)
	 */
	public $prevent_edit = false;

	/**
	 * @var boolean Whether we need to add the autocomplete code to the top of the page for the purposes of this field
	 */
	public $uses_autocomplete = false;


	/**
	 * Displays the particular field as a form item
	 *
	 * (Borrowed from old Jelly)
	 *
	 * @param string $prefix The prefix to put before the filename to be rendered
	 * @return View
	 **/
	public function input($prefix = 'jelly/field', $data = array())
	{
		// Make sure there is an 'attrs' array set to prevent error in view
		$attrs = Arr::get($data, 'attributes', array());

		// And push in any classes specified in the css_class variable
		if (count($this->css_class)) {
			$css_class_attr = Arr::get($attrs, 'class', '');
			if ($css_class_attr != '') {
				$css_class_attr .= ' ';
			}
			$attrs['class'] = $css_class_attr . implode(' ', $this->css_class);
		}

		if (in_array(array('not_empty'), $this->rules)) {
			if (!$this instanceof Jelly_Field_File || !$data['value']) {
				$attrs['required'] = 'required';
			}
		}

		$data['attributes'] = $attrs;

		// Get the view name
		$view = $this->_input_view($prefix);

		// Grant acces to all of the vars plus the field object
		$data = array_merge(get_object_vars($this), $data, array('field' => $this));

		// Make sure there is an 'attrs' array set to prevent error in view
		$data['attributes'] = Arr::get($data, 'attributes', array());

		// By default, a view object only needs a few defaults to display it properly
		return View::factory($view, $data);
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
		return Html::chars((string)$value);
	}

	/**
	 * Used internally to allow fields to inherit input views from parent classes
	 *
	 * (Borrowed from old Jelly)
	 *
	 * @param   Jelly_Field  $class [optional]
	 * @return  string
	 */
	protected function _input_view($prefix, $field_class = NULL)
	{
		if (is_null($field_class))
		{
			$field_class = get_class($this);
		}

		// Determine the view name, which matches the class name
		$file = strtolower($field_class);

		// Could be prefixed by Jelly_Field, or just Field_
		$file = str_replace(array('jelly_field_', 'field_'), array('', ''), $file);

		// Allowing a prefix means inputs can be rendered from different paths
		$view = $prefix.'/'.$file;

		// Check we can find a view for this field type, if not inherit view from parent
		if ( ! Kohana::find_file('views', $view)
			// Don't try going beyond this base Jelly_Field class!
			AND get_parent_class($field_class) !== __CLASS__)
		{
			return $this->_input_view($prefix, get_parent_class($field_class));
		}

		// Either we've found a suitable view or there is no suitable one so just return what it should be
		return $view;
	}
}
