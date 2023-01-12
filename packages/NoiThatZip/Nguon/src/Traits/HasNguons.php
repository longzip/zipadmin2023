<?php

declare(strict_types=1);

/*
 * This file is part of Laravel nguons.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NoiThatZip\Nguon\Traits;

use NoiThatZip\Nguon\Models\Nguon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasNguons
{
    public function nguons(): MorphToMany
    {
        return $this->morphToMany(
            config('nguon.models.nguon'),
            'model',
            'model_has_nguons'
        );
    }

    public function nguonsList(): array
    {
        return $this->nguons()
                    ->pluck('name', 'id')
                    ->toArray();
    }

    public function assignNguon(...$nguons)
    {
        $nguons = collect($nguons)
            ->flatten()
            ->map(function ($nguon) {
                return $this->getStoredNguon($nguon);
            })
            ->all();

        $this->nguons()->saveMany($nguons);

        return $this;
    }

    public function removeNguon($nguon)
    {
        $this->nguons()->detach($this->getStoredNguon($nguon));
    }

    public function syncnguons(...$nguons)
    {
        $this->nguons()->detach();

        return $this->assignNguon($nguons);
    }

    public function hasNguon($nguons)
    {
        if (is_string($nguons)) {
            return $this->nguons->contains('name', $nguons);
        }

        if ($nguons instanceof Nguon) {
            return $this->nguons->contains('id', $nguons->id);
        }

        if (is_array($nguons)) {
            foreach ($nguons as $nguon) {
                if ($this->hasNguon($nguon)) {
                    return true;
                }
            }

            return false;
        }

        return $nguons->intersect($this->nguons)->isNotEmpty();
    }

    public function hasAnyNguon($nguons)
    {
        return $this->hasNguon($nguons);
    }

    public function hasAllnguons($nguons)
    {
        if (is_string($nguons)) {
            return $this->nguons->contains('name', $nguons);
        }

        if ($nguons instanceof Nguon) {
            return $this->nguons->contains('id', $nguons->id);
        }

        $nguons = collect()->make($nguons)->map(function ($nguon) {
            return $nguon instanceof Nguon ? $nguon->name : $nguon;
        });

        return $nguons->intersect($this->nguons->pluck('name')) === $nguons;
    }

    protected function getStoredNguon($nguon): Nguon
    {
        if (is_numeric($nguon)) {
            return app(Nguon::class)->findById($nguon);
        }

        if (is_string($nguon)) {
            return app(Nguon::class)->findByName($nguon);
        }

        return $nguon;
    }
}
