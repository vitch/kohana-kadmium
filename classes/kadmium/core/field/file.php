<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Core_Field_File extends Jelly_Core_Field_File
{
	public $web_path = '';

	public function get_web_path($path, $thumbnail_index = -1)
	{
		return $this->web_path ? $this->web_path : str_replace(DOCROOT, '', $path);
	}
}