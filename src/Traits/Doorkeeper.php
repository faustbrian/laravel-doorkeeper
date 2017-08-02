<?php

/*
 * This file is part of Laravel Doorkeeper.
 *
 * (c) Brian Faust <hello@brianfaust.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BrianFaust\Doorkeeper\Traits;

use BrianFaust\Doorkeeper\Listeners\DoorkeeperListener;

trait Doorkeeper
{
    public static function bootDoorkeeper()
    {
        static::loaded(DoorkeeperListener::class.'@compare');
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
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

    /**
     * @param $callback
     * @param int $priority
     */
    public static function loaded($callback, $priority = 0)
    {
        static::registerModelEvent('loaded', $callback, $priority);
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function maxed($key = null): bool
    {
        if ($key !== null) {
            return session()->has('doorkeeper_reached_'.$key);
        }

        return session()->has('doorkeeper_reached_maximum');
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function current($key = null): ?int
    {
        if ($key !== null) {
            return session()->get('doorkeeper_count_'.$key);
        }

        return session()->get('doorkeeper_overall_count');
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function allowed($key = null)
    {
        if ($key !== null) {
            return $this->limitations->get($key);
        }

        return $this->limitations->sum();
    }

    /**
     * @param $limits
     *
     * @return $this
     */
    public function limits($limits)
    {
        $this->limitations = collect($limits);

        $listener = new DoorkeeperListener();
        $listener->compare($this);

        return $this;
    }

    /**
     * @param null $key
     *
     * @return bool
     */
    public function passes($key = null): bool
    {
        return ! $this->maxed($key);
    }

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function fails($key = null): bool
    {
        return $this->maxed($key);
    }
}
