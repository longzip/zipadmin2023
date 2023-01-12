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
namespace NoiThatZip\Task\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Kalnoy\Nestedset\NodeTrait;
use Artisanry\Commentable\Traits\HasComments;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Task extends Model implements HasMedia
{
    use NodeTrait, HasComments, HasMediaTrait;
        /**
     * @var array
     */
        protected $guarded = ['id', 'created_at', 'updated_at'];
        /**
     * @return bool
     */
        public function hasChildren(): bool
        {
            return $this->children()->count() > 0;
        }
        /**
     * @return mixed
     */
        public function tasklog(): MorphTo
        {
            return $this->morphTo();
        }
        /**
     * @return mixed
     */
        public function creator(): MorphTo
        {
            return $this->morphTo('creator');
        }
        /**
     * @param Model $commentable
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
        public function createTask(Model $tasklog, $data, Model $creator): self
        {
            return $tasklog->tasks()->create(array_merge($data, [
                'creator_id'   => $creator->getAuthIdentifier(),
                'creator_type' => $creator->getMorphClass(),
            ]));
        }
        /**
     * @param $id
     * @param $data
     *
     * @return mixed
     */
        public function updateTask($id, $data): bool
        {
            return (bool) static::find($id)->update($data);
        }
        /**
     * @param $id
     *
     * @return mixed
     */
        public function deleteTask($id): bool
        {
            return (bool) static::find($id)->delete();
        }
    }
