<?php

namespace DraperStudio\Doorkeeper\Contracts;

interface DoorkeeperContract
{
    public static function bootDoorkeeper();

    public function newFromBuilder($attributes = []);

    public static function loaded($callback, $priority = 0);

    public function maxed($key = null);

    public function current($key = null);

    public function allowed($key = null);

    public function limits($limits);

    public function passes($key = null);

    public function fails($key = null);
}
