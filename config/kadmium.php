<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'site_name' => 'Kadmium demo site',
	'results_per_list_page' => 10,
	'base_path' => 'admin',
	'navigation_controllers' => array(),
	'valid_parent_controllers' => '.*', /* Override in application config to only allow certain parent/ child relationships to match route */
	'valid_child_controllers' => '.*', /* Override in application config to only allow certain parent/ child relationships to match route */
);
