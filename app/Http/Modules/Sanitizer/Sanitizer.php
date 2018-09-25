<?php

namespace App\Http\Modules\Sanitizer;

class Sanitizer
{
	
	/**
	 * Removes html and php tags, converts to html entities, and
	 * Strip the slashes from the var
	 *
	 * @param String $var
	 * @return String cleansed variable
	 */
	protected function clean($var)
	{
		return stripslashes(htmlentities(strip_tags($var)));
	}
}