<?php

namespace NoiThatZip\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use NoiThatZip\Costcenter\Traits\HasCostcenters;
use BrianFaust\Categories\Traits\HasCategories;
use NoiThatZip\ProductLine\Traits\HasProductLines;

class Order extends Model
{
    use HasCostcenters, HasCategories, HasProductLines;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    /**
     * @return mixed
     */
    public function orderable(): MorphTo
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
    public function createOrder(Model $orderable, $data, Model $creator): self
    {
        return $orderable->orders()->create(array_merge($data, [
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
    public function updateOrder($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteOrder($id): bool
    {
        return (bool) static::find($id)->delete();
    }
}
