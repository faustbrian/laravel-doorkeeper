<?php

/*
 * This file is part of Laravel Doorkeeper.
 *
 * (c) DraperStudio <hello@draperstudio.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DraperStudio\Doorkeeper\Listeners;

use DraperStudio\Doorkeeper\Contracts\ListenerContract;

/**
 * Class DoorkeeperListener.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class DoorkeeperListener implements ListenerContract
{
    /**
     * @param $model
     *
     * @return mixed
     */
    public function compare($model)
    {
        // Flush session before each check
        $session = session()->get(null);
        foreach ($session as $key => $value) {
            if (starts_with($key, 'doorkeeper_')) {
                session()->forget($key);
            }
        }

        // Limits
        $limits = $model->limitations;

        // No limits
        if ($limits->isEmpty()) {
            return $model;
        }

        // Check each relation and limitation
        $overallCount = 0;
        foreach ($limits as $relation => $limit) {
            if (!$model->$relation) {
                continue;
            }

            $relationCount = $model->$relation->count();
            $overallCount += $relationCount;

            if ($relationCount >= $limit) {
                session()->put('doorkeeper_reached_'.$relation, true);
            }

            session()->put('doorkeeper_count_'.$relation, $relationCount);
        }
        // Check if the overall limit has been reached
        if ($overallCount >= $limits->sum()) {
            session()->put('doorkeeper_reached_maximum', true);
        }

        session()->put('doorkeeper_overall_count', $overallCount);
    }
}
