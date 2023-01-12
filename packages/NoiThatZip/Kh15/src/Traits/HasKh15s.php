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

namespace NoiThatZip\Kh15\Traits;

use NoiThatZip\Kh15\Models\Kh15;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasKh15s
{
    /**
     * @return string
     */
    public function kh15Model(): string
    {
        return config('kh15.model');
    }

    /**
     * @return mixed
     */
    public function kh15s(): MorphMany
    {
        return $this->morphMany($this->kh15Model(), 'kh15log');
    }

    /**
     * @param $data
     * @param Model      $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function kh15($data, Model $creator)
    {
        $kh15Model = $this->kh15Model();

        $kh15 = (new $kh15Model())->createKh15($this, $data, $creator);

        return $kh15;
    }

    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateKh15($id, $data)
    {
        $kh15Model = $this->kh15Model();

        $kh15 = (new $kh15Model())->updateKh15($id, $data);

        return $kh15;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteKh15($id): bool
    {
        $kh15Model = $this->kh15Model();

        return (bool) (new $kh15Model())->deleteKh15($id);
    }

    /**
     * @return mixed
     */
    public function Kh15Count(): int
    {
        return $this->kh15s->count();
    }
}
