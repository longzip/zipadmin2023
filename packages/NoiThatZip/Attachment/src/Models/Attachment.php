<?php

namespace NoiThatZip\Attachment\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return mixed
     */
    public function attachable(): MorphTo
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
     * @param Model $attachable
     * @param $data
     * @param Model $creator
     *
     * @return static
     */
    public function createAttach(Model $attachable, $data, Model $creator): self
    {
        return $attachable->attachs()->create(array_merge($data, [
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
    public function updateAttach($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteAttach($id): bool
    {
        return (bool) static::find($id)->delete();
    }
}
