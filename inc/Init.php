<?php

namespace Inc;

use Services\WordFilterOptions;
use Services\WordFilter;

class init
{
    public static function get_services()
    {
        return [
            WordFilter::class,
            WordFilterOptions::class
        ];
    }

    public function register_services()
    {
        foreach (self::get_services() as $class) {
            $this->instantiate($class);
        }
    }

    public function instantiate($class)
    {
        $instance = new $class;
        return $instance;
    }
}