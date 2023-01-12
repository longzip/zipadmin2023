<?php

namespace NoiThatZip\Quote\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use NoiThatZip\ProductLine\Traits\HasProductLines;

class Quote extends Model
{
    use HasCostcenters, HasProductLines;
    /**
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];
    /**
     * @return mixed
     */
    public function quoteable(): MorphTo
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
    public function createQuote(Model $quoteable, $data, Model $creator): self
    {
        return $quoteable->quotes()->create(array_merge($data, [
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
    public function updateQuote($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteQuote($id): bool
    {
        return (bool) static::find($id)->delete();
    }
}
