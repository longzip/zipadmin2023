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
namespace NoiThatZip\SalesTarget\Traits;
use NoiThatZip\SalesTarget\Models\SalesTarget;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\MorphMany;
trait HasSalesTarget
{
    public function salesTargets(): MorphMany
    {
        return $this->morphMany(SalesTarget::class, 'targetable');
    }
    public function addTarget($kh15_number, $number, $order_number, $amount_number, $close, $dahlia, $khac, $iris, $ivy): bool
    {
        $salesTarget = new SalesTarget([
            'number'   => $number,
            'close' => new Carbon($close),
        ]);
        if ($kh15_number) {
            $salesTarget->kh15_number = $kh15_number;
        }
        if ($order_number) {
            $salesTarget->order_number = $order_number;
        }
        if ($amount_number) {
            $salesTarget->amount_number = $amount_number;
        }
        if ($ivy) {
            $salesTarget->ivy = $ivy;
        }else{
            $salesTarget->ivy = 0;
        }
        if ($dahlia) {
            $salesTarget->dahlia = $dahlia;
        }else{
            $salesTarget->dahlia = 0;
        }
        if ($iris) {
            $salesTarget->iris = $iris;
        }else{
            $salesTarget->iris = 0;
        }
        if ($khac) {
            $salesTarget->khac = $khac;
        }else{
            $salesTarget->khac = 0;
        }
        return (bool) $this->salesTargets()->save($salesTarget);
    }
}
