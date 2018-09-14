<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
        View::share('navigation_entries', User::getCorrespondingNavigationEntry());
	}
	
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
