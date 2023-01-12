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

namespace NoiThatZip\Order\Traits;

use NoiThatZip\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Hasorders
{
  /**

     * @return string

     */

    public function orderModel(): string
    {
        return config('order.model');
    }

    /**
     * @return mixed
     */

    public function orders(): MorphMany
    {
        return $this->morphMany($this->orderModel(), 'orderable');
    }



    /**

     * @param $data

     * @param Model      $creator

     * @param Model|null $parent

     *

     * @return static

     */

    public function order($data, Model $creator)
    {
        $orderModel = $this->orderModel();
        $order = (new $orderModel())->createOrder($this, $data, $creator);
        return $order;
    }



    /**

     * @param $id

     * @param $data

     * @param Model|null $parent

     *

     * @return mixed

     */

    public function updateorder($id, $data)
    {
        $orderModel = $this->orderModel();
        $order = (new $orderModel())->updateOrder($id, $data);
        return $order;
    }



    /**

     * @param $id

     *

     * @return mixed

     */

    public function deleteOrder($id): bool

    {

        $orderModel = $this->orderModel();



        return (bool) (new $orderModel())->deleteOrder($id);

    }



    /**

     * @return mixed

     */

    public function orderCount(): int

    {

        return $this->orders->count();

    }

}

