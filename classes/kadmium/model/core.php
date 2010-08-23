<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Model_Core extends Jelly_Model_Core
{

	// Never provide UI to delete this model
	const DELETE_NEVER = 0;
	// Automatically delete any children (through relationships) that this model has
	const DELETE_ALL_CHILDREN = 1;
	// Only allow delete to succed if there are no unique child
	const DELETE_ONLY_SPINSTER = 2;

	const NO_FOREIGN_KEY = 'THEREISREALLYNOFOREIGNKEY-DONNOTGUESSONEFORME!';

	public $delete_policy = Kadmium_Model_Core::DELETE_ONLY_SPINSTER;
}
