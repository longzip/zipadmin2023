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

namespace NoiThatZip\Lost\Traits;

use NoiThatZip\Lost\Models\Lost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLosts
{
    /**
     * @return string
     */
    public function lostModel(): string
    {
        return config('lost.model');
    }

    /**
     * @return mixed
     */
    public function losts(): MorphMany
    {
        return $this->morphMany($this->lostModel(), 'lostlog');
    }

    /**
     * @param $data
     * @param Model      $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function lost($data, Model $creator)
    {
        $lostModel = $this->lostModel();

        $lost = (new $lostModel())->createLost($this, $data, $creator);

        return $lost;
    }

    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateLost($id, $data)
    {
        $lostModel = $this->lostModel();

        $lost = (new $lostModel())->updateLost($id, $data);

        return $lost;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteLost($id): bool
    {
        $lostModel = $this->lostModel();

        return (bool) (new $lostModel())->deleteLost($id);
    }

    /**
     * @return mixed
     */
    public function lostCount(): int
    {
        return $this->losts->count();
    }
}
