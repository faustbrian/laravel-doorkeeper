<?php

/*
 * This file is part of Laravel Doorkeeper.
 *
 * (c) Brian Faust <hello@brianfaust.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
