<?php



declare(strict_types=1);

namespace BrianFaust\Doorkeeper\Contracts;

interface ListenerContract
{
    /**
     * @param $model
     *
     * @return mixed
     */
    public function compare($model);
}
