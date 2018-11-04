<?php

namespace App\Http\Modules\Generator;

class ListGenerator
{

    protected $array = [];
    protected static $_instance;

    /**
     * Creates a list of array from the given arguments
     *
     * @param array ...$args
     * @return void
     */
    public static function makeArray(...$args)
    {

        self::$_instance !== null ?: self::$_instance = new self;
        array_walk_recursive($args, function($arg) {
            if(isset($arg)) {
                self::$_instance->array[] = $arg;
            }
        });

        return self::$_instance;
        
    }

    /**
     * removes duplicate values
     *
     * @return void
     */
    public function unique()
    {
        return array_unique($this->array);
    }

    /**
     * Merge the list of array into a single list
     *
     * @return void
     */
    public function merge()
    {
        return $this->array;
    }
}