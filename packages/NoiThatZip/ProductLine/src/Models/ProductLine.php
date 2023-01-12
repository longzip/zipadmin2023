<?php

namespace NoiThatZip\ProductLine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProductLine extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

        /**
     * @return mixed
     */

    public function productable(): MorphTo
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

    public function createProductLine(Model $productable, $data, Model $creator): self
    {
        return $productable->productLines()->create(array_merge($data, [
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

    public function updateProductLine($id, $data): bool
    {
        return (bool) static::find($id)->update($data);
    }



    /**
     * @param $id
     *
     * @return mixed
     */

    public function deleteProductLine($id): bool
    {
        return (bool) static::find($id)->delete();
    }

    public function products(){
        return $this->belongsTo('App\Product');
    }
}
