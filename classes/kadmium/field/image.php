<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Image extends Jelly_Field_Image
{

	public $web_path = '';

	public function display($model, $value)
	{
		$path = count($this->thumbnails) ? $this->thumbnails[0]['path'] : $this->path;
		return $value == '' ? $value : Html::image($this->get_web_path($path).$value);
	}

	public function get_web_path($path)
	{
		return $this->web_path ? $this->web_path : str_replace(DOCROOT, '', $path);
	}

}