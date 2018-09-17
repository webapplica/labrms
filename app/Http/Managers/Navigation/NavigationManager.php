<?php

namespace App\Http\Managers\Navigation;

use App\Http\Interfaces\Navigation\NavigationInterface;

class NavigationManager implements NavigationInterface
{
    private static $defaultEntryPoint = 0;
    private static $guestEntryPoint = 5;
    private $path;
    private static $_instance;
    private static $entry = [
        'navigation.admin',
        'navigation.assistant',
        'navigation.staff',
        'navigation.faculty',
        'navigation.student',
        'navigation.guest',
    ];

    /**
     * Undocumented function
     *
     * @param integer $id
     * @return void
     */
    public static function search($id)
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }

        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        self::$_instance->path = isset(self::$entry[$id]) ? self::$entry[$id] : self::$guestEntryPoint;

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