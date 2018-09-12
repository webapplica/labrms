<?php

namespace App\Http\Managers\Navigation;

use App\Http\Interfaces\NavigationEntries\NavigationEntriesInterface;

class NavigationEntriesManager implements NavigationEntriesInterface
{
    private $basePath;

    public function __construct()
    {
        $this->basePath = app_path();
    }

    public function get($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        switch($id) {
            case 0: 
                $list = config('app.navigation.admin');
                break;
            case 1: 
                $list = config('app.navigation.admin');
                break;
            case 2: 
                $list = config('app.navigation.admin');
                break;
            case 3: 
                $list = config('app.navigation.admin');
                break;
            case 4: 
                $list = config('app.navigation.admin');
                break;
        }
    }


}