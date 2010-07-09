<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'results_per_list_page' => 10,
	'base_path' => 'admin',
	'valid_parent_controllers' => '.*', /* Override in application config to only allow certain parent/ child relationships to match route */
	'valid_child_controllers' => '.*', /* Override in application config to only allow certain parent/ child relationships to match route */
);
