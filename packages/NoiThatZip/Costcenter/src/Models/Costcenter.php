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

namespace NoiThatZip\Costcenter\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use NoiThatZip\SalesTarget\Traits\HasSalesTarget;
use EloquentFilter\Filterable;

class Costcenter extends Model
{
    use NodeTrait, HasSlug, HasSalesTarget, Filterable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['pivot'];

    public function costcenters(): MorphTo
    {
        return $this->morphTo();
    }

    public function entries(string $class): MorphToMany
    {
        return $this->morphedByMany($class, 'model', 'model_has_costcenters');
    }

    public static function tree(): array
    {
        return static::get()->toTree()->toArray();
    }

    public static function findByName(string $name): self
    {
        return static::where('name', $name)->orWhere('slug', $name)->firstOrFail();
    }

    public static function findById(int $id): self
    {
        return static::findOrFail($id);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }
}
