<?php

namespace App\Http\Interfaces\Navigation;

interface NavigationInterface
{
    static function search(int $id);
    function left();
    function right();
    function base();
    function extract();
}