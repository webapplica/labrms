<?php

namespace App\Http\Managers\Navigation;

use App\Http\Interfaces\Navigation\NavigationEntriesInterface;

class NavigationEntriesManager implements NavigationEntriesInterface
{
    private static $defaultEntryPoint = 0;
    private static $entry = [
        'app.navigation.admin',
        'app.navigation.assistant',
        'app.navigation.staff',
        'app.navigation.faculty',
        'app.navigation.student',
    ];

    public static function get(int $id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        return config( isset(self::$entry[$id]) ? self::$entry[$id] : self::$entry[ self::$defaultEntryPoint ] );
    }


}