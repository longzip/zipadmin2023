<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Costcenters.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiThatZip\Costcenter\Traits;

use NoiThatZip\Costcenter\Models\Costcenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCostcenters
{
    public function costcenters(): MorphToMany
    {
        return $this->morphToMany(
            config('costcenter.models.costcenter'),
            'model',
            'model_has_costcenters'
        );
    }

    public function costcentersList()
    {
        return $this->costcenters()->select('id','name')->get();
    }

    public function costcentersListAll()
    {
        return $this->costcenters()->get();
    }

    public function assignCostcenter(...$costcenters)
    {
        $costcenters = collect($costcenters)
            ->flatten()
            ->map(function ($costcenter) {
                return $this->getStoredCostcenter($costcenter);
            })
            ->all();

        $this->costcenters()->saveMany($costcenters);

        return $this;
    }

    public function removeCostcenter($costcenter)
    {
        $this->costcenters()->detach($this->getStoredCostcenter($costcenter));
    }

    public function syncCostcenters(...$costcenters)
    {
        $this->costcenters()->detach();

        return $this->assignCostcenter($costcenters);
    }

    public function hasCostcenter($costcenters)
    {
        if (is_string($costcenters)) {
            return $this->costcenters->contains('name', $costcenters);
        }

        if ($costcenters instanceof Costcenter) {
            return $this->costcenters->contains('id', $costcenters->id);
        }

        if (is_array($costcenters)) {
            foreach ($costcenters as $costcenter) {
                if ($this->hasCostcenter($costcenter)) {
                    return true;
                }
            }

            return false;
        }

        return $costcenters->intersect($this->costcenters)->isNotEmpty();
    }

    public function hasAnyCostcenter($costcenters)
    {
        return $this->hasCostcenter($costcenters);
    }

    public function hasAllCostcenters($costcenters)
    {
        if (is_string($costcenters)) {
            return $this->costcenters->contains('name', $costcenters);
        }

        if ($costcenters instanceof Costcenter) {
            return $this->costcenters->contains('id', $costcenters->id);
        }

        $costcenters = collect()->make($costcenters)->map(function ($costcenter) {
            return $costcenter instanceof Costcenter ? $costcenter->name : $costcenter;
        });

        return $costcenters->intersect($this->costcenters->pluck('name')) === $costcenters;
    }

    protected function getStoredCostcenter($costcenter): Costcenter
    {
        if (is_numeric($costcenter)) {
            return app(Costcenter::class)->findById($costcenter);
        }

        if (is_string($costcenter)) {
            return app(Costcenter::class)->findByName($costcenter);
        }

        return $costcenter;
    }
}
