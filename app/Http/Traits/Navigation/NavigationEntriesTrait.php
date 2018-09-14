<?php

namespace App\Http\Traits\Navigation;

use Auth;
use App\Http\Managers\Navigation\NavigationEntriesManager;

trait NavigationEntriesTrait
{
    protected static function getCorrespondingNavigationEntry()
    {
        // dd(Auth::user());
        // $id = Auth::user()->id;
        // return NavigationEntriesManager::get($id);
    }
}