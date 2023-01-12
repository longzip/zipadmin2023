<?php
/*
 * This file is part of Laravel Commentable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NoiThatZip\Video\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class Video extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    /**
     * @return mixed
     */
    public function videolog(): MorphTo
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
    public function createVideo(Model $videolog, $data, Model $creator): self
    {
        return $videolog->videos()->create(array_merge($data, [
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
    public function updateVideo($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteVideo($id): bool
    {
        return (bool) static::find($id)->delete();
    }
}
