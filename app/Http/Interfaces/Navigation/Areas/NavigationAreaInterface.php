<?php

namespace App\Http\Interfaces\Navigation\Areas;

interface NavigationAreaInterface
{
	static function access(int $id);
	function left();
	function right();
	function base();
	function center();
	function all();
	function get();
}