<?php

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
