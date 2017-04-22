<?php



declare(strict_types=1);

namespace BrianFaust\Doorkeeper\Contracts;

interface DoorkeeperContract
{
    /**
     * @return mixed
     */
    public static function bootDoorkeeper();

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function newFromBuilder($attributes = []);

    /**
     * @param $callback
     * @param int $priority
     *
     * @return mixed
     */
    public static function loaded($callback, $priority = 0);

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function maxed($key = null);

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function current($key = null);

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function allowed($key = null);

    /**
     * @param $limits
     *
     * @return mixed
     */
    public function limits($limits);

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function passes($key = null);

    /**
     * @param null $key
     *
     * @return mixed
     */
    public function fails($key = null);
}
