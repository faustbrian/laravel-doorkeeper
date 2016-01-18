<?php

namespace DraperStudio\Doorkeeper\Traits;

use DraperStudio\Doorkeeper\Listeners\DoorkeeperListener;

trait Doorkeeper
{
    public static function bootDoorkeeper()
    {
        static::loaded(DoorkeeperListener::class.'@compare');
    }

    public function newFromBuilder($attributes = [])
    {
        $instance = parent::newFromBuilder($attributes);

        if (empty($instance->limitations)) {
            return $instance;
        }

        $instance->limitations = collect($this->limitations);
        $instance->fireModelEvent('loaded');

        return $instance;
    }

    public static function loaded($callback, $priority = 0)
    {
        static::registerModelEvent('loaded', $callback, $priority);
    }

    public function maxed($key = null)
    {
        if ($key !== null) {
            return session()->has('doorkeeper_reached_'.$key);
        }

        return session()->has('doorkeeper_reached_maximum');
    }

    public function current($key = null)
    {
        if ($key !== null) {
            return session()->get('doorkeeper_count_'.$key);
        }

        return session()->get('doorkeeper_overall_count');
    }

    public function allowed($key = null)
    {
        if ($key !== null) {
            return $this->limitations->get($key);
        }

        return $this->limitations->sum();
    }

    public function limits($limits)
    {
        $this->limitations = collect($limits);

        $listener = new DoorkeeperListener();
        $listener->compare($this);

        return $this;
    }

    public function passes($key = null)
    {
        return !$this->maxed($key);
    }

    public function fails($key = null)
    {
        return $this->maxed($key);
    }
}
