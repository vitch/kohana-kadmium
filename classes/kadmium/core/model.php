<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Model extends Jelly_Core_Model
{

	// Never provide UI to delete this model
	const DELETE_NEVER = 0;
	// Automatically delete any children (through relationships) that this model has
	const DELETE_ALL_CHILDREN = 1;
	// Only allow delete to succed if there are no unique child
	const DELETE_ONLY_SPINSTER = 2;

	public $delete_policy = Kadmium_Core_Model::DELETE_ONLY_SPINSTER;

	// Get's the non-relational fields of this model cleaned for output...
	public function get_xss_cleaned()
	{
		$cleaned = array();
		foreach($this->meta()->fields() as $field_id=>$field) {
			if ($field instanceof Jelly_Field_Relationship) {
				continue;
			}
			$cleaned[$field_id] = Html::chars($this->get($field_id));
		}
		return (object)$cleaned;
	}

	// Util method so you can get the actual value for a relationship field without executing a query and
	// calling ->id() on the loaded relation (sometimes you just want the id and not the related object).
	public function get_raw($name, $changed = TRUE)
	{
		if ($field = $this->_meta->fields($name))
		{
			// Alias the name to its actual name
			$name = $field->name;

			if ($changed AND array_key_exists($name, $this->_changed))
			{
				return $this->_changed[$name];
			}
			else
			{
				return $this->_original[$name];
			}
		}
		// Return unmapped data from custom queries
		elseif (isset($this->_unmapped[$name]))
		{
			return $this->_unmapped[$name];
		}
	}
}
