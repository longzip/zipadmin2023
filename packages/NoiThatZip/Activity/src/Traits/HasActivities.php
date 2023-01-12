<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Commentable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiThatZip\Activity\Traits;

use NoiThatZip\Activity\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasActivities
{
    /**
     * @return string
     */
    public function activityModel(): string
    {
        return config('activity.model');
    }

    /**
     * @return mixed
     */
    public function activities(): MorphMany
    {
        return $this->morphMany($this->activityModel(), 'activitylog');
    }

    /**
     * @param $data
     * @param Model      $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function activity($data, Model $creator, Model $parent = null)
    {
        $activityModel = $this->activityModel();

        $activity = (new $activityModel())->createActivity($this, $data, $creator);

        if (!empty($parent)) {
            $parent->appendNode($activity);
        }

        return $activity;
    }

    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateActivity($id, $data, Model $parent = null)
    {
        $activityModel = $this->activityModel();

        $activity = (new $activityModel())->updateActivity($id, $data);

        if (!empty($parent)) {
            $parent->appendNode($activity);
        }

        return $activity;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteActivity($id): bool
    {
        $activitykModel = $this->activitykModel();

        return (bool) (new $activitykModel())->deleteActivityk($id);
    }

    /**
     * @return mixed
     */
    public function activityCount(): int
    {
        return $this->activities->count();
    }
}
