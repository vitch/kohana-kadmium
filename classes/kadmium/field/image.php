<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kadmium_Field_Image extends Jelly_Field_Image
{

	public function display($model, $value)
	{
		$path = count($this->thumbnails) ? $this->thumbnails[0]['path'] : $this->path;
		return $value == '' ? $value : Html::image(str_replace(DOCROOT, '', $path).$value);
	}
}