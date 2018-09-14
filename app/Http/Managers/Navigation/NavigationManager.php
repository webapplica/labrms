<?php

namespace App\Http\Managers\Navigation;

use App\Http\Interfaces\Navigation\NavigationInterface;

class NavigationManager implements NavigationInterface
{
    private static $defaultEntryPoint = 0;
    private $path;
    private static $_instance;
    private static $entry = [
        'navigation.admin',
        'navigation.assistant',
        'navigation.staff',
        'navigation.faculty',
        'navigation.student',
    ];

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public static function search(int $id)
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }

        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        self::$_instance->path = self::$entry[$id];

        return self::$_instance;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function left()
    {
        return $this->path .= '.left';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function right()
    {
        return $this->path .= '.right';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function center()
    {
        return $this->path .= '.center';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function base()
    {
        return $this->path .= '.base';
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function extract()
    {
        return config($this->path);
    }


}