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

namespace NoiThatZip\ProductLine\Traits;

use NoiThatZip\ProductLine\Models\ProductLine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasProductLines
{
  /**

     * @return string

     */

    public function productLineModel(): string
    {
        return config('productline.model');
    }

    /**
     * @return mixed
     */

    public function productLines(): MorphMany
    {
        return $this->morphMany($this->productLineModel(), 'productable');
    }



    /**

     * @param $data

     * @param Model      $creator

     * @param Model|null $parent

     *

     * @return static

     */

    public function productLine($data, Model $creator)
    {
        $productLineModel = $this->productLineModel();
        $productLine = (new $productLineModel())->createProductLine($this, $data, $creator);
        return $productLine;
    }



    /**

     * @param $id

     * @param $data

     * @param Model|null $parent

     *

     * @return mixed

     */

    public function updateProductLine($id, $data)
    {
        $productLineModel = $this->productLineModel();
        $productLine = (new $productLineModel())->updateProductLine($id, $data);
        return $productLine;
    }



    /**

     * @param $id

     *

     * @return mixed

     */

    public function deleteProductLine($id): bool

    {

        $productLineModel = $this->productLineModel();



        return (bool) (new $productLineModel())->deleteProductLine($id);

    }



    /**

     * @return mixed

     */

    public function productLineCount(): int

    {

        return $this->productLines->count();

    }

}

